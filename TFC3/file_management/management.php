<?php
require '../includes/db_connect.php';

class FileManager {
    private $db;
    private $conn;
    private $userId;
    private $userRole;

    // Upload a file
    public function uploadFile($file, $folderId = null) {
        try {
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];

            // Get file type
            $fileType = mime_content_type($fileTmpName);
            
            // Validate file type
            $stmt = $this->conn->prepare("SELECT id FROM file_types WHERE mime_type = :mime_type");
            $stmt->execute(['mime_type' => $fileType]);
            $fileTypeRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$fileTypeRecord) {
                throw new Exception("Unsupported file type");
            }

            // Generate unique file path
            $uploadDir = '../uploads/';
            $uniqueFileName = uniqid() . '_' . $fileName;
            $uploadPath = $uploadDir . $uniqueFileName;

            // Move uploaded file
            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                // Insert file record
                $stmt = $this->conn->prepare("
                    INSERT INTO files 
                    (user_id, folder_id, file_name, file_path, file_size, file_type_id) 
                    VALUES (:user_id, :folder_id, :file_name, :file_path, :file_size, :file_type_id)
                ");
                $stmt->execute([
                    'user_id' => $this->userId,
                    'folder_id' => $folderId,
                    'file_name' => $fileName,
                    'file_path' => $uploadPath,
                    'file_size' => $fileSize,
                    'file_type_id' => $fileTypeRecord['id']
                ]);

                // Log file history
                $this->logFileHistory($this->conn->lastInsertId(), 'upload');

                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("File upload error: " . $e->getMessage());
            return false;
        }
    }

    // Get user's files
    public function getUserFiles() {
        $query = "SELECT f.*, ft.mime_type, ft.description 
                  FROM files f 
                  JOIN file_types ft ON f.file_type_id = ft.id 
                  WHERE f.user_id = :user_id AND f.is_deleted = 0 
                  ORDER BY f.upload_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['user_id' => $this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create folder
    public function createFolder($folderName, $parentFolderId = null) {
        $stmt = $this->conn->prepare("
            INSERT INTO folders (user_id, folder_name, parent_folder_id) 
            VALUES (:user_id, :folder_name, :parent_folder_id)
        ");
        return $stmt->execute([
            'user_id' => $this->userId,
            'folder_name' => $folderName,
            'parent_folder_id' => $parentFolderId
        ]);
    }

    // Get user's folders
    public function getUserFolders() {
        $stmt = $this->conn->prepare("
            SELECT * FROM folders 
            WHERE user_id = :user_id AND is_deleted = 0 
            ORDER BY created_at DESC
        ");
        $stmt->execute(['user_id' => $this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Log file history
    private function logFileHistory($fileId, $changeType, $details = null) {
        $stmt = $this->conn->prepare("
            INSERT INTO file_history 
            (file_id, user_id, change_type, details) 
            VALUES (:file_id, :user_id, :change_type, :details)
        ");
        return $stmt->execute([
            'file_id' => $fileId,
            'user_id' => $this->userId,
            'change_type' => $changeType,
            'details' => $details
        ]);
    }

    // Delete file (soft delete)
    public function deleteFile($fileId) {
        $stmt = $this->conn->prepare("
            UPDATE files 
            SET is_deleted = 1 
            WHERE id = :file_id AND user_id = :user_id
        ");
        $result = $stmt->execute([
            'file_id' => $fileId,
            'user_id' => $this->userId
        ]);

        if ($result) {
            $this->logFileHistory($fileId, 'delete');
        }
        return $result;
    }

    // Share file
    public function shareFile($fileId, $sharedWithUserId, $permissions = 'view') {
        // Check if user has permission to share (for managers)
        if ($this->userRole !== 'manager' && $this->userRole !== 'admin') {
            return false;
        }

        $stmt = $this->conn->prepare("
            INSERT INTO shares 
            (file_id, user_id, shared_by, permissions) 
            VALUES (:file_id, :user_id, :shared_by, :permissions)
        ");
        return $stmt->execute([
            'file_id' => $fileId,
            'user_id' => $sharedWithUserId,
            'shared_by' => $this->userId,
            'permissions' => $permissions
        ]);
    }

    // Get shared files
    public function getSharedFiles() {
        $stmt = $this->conn->prepare("
            SELECT f.*, s.permissions, u.username as shared_by 
            FROM shares s
            JOIN files f ON s.file_id = f.id
            JOIN users u ON s.shared_by = u.id
            WHERE s.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $this->userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get file versions
    public function getFileVersions($fileId) {
        $stmt = $this->conn->prepare("
            SELECT * FROM file_versions 
            WHERE file_id = :file_id 
            ORDER BY uploaded_at DESC
        ");
        $stmt->execute(['file_id' => $fileId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Helper function to generate file icon based on MIME type
function getFileIcon($mimeType) {
    $iconMap = [
        'image/jpeg' => 'fa-file-image',
        'image/png' => 'fa-file-image',
        'application/pdf' => 'fa-file-pdf',
        'text/plain' => 'fa-file-alt',
        'application/msword' => 'fa-file-word',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'fa-file-word',
        'application/vnd.ms-excel' => 'fa-file-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fa-file-excel'
    ];

    return $iconMap[$mimeType] ?? 'fa-file';
}