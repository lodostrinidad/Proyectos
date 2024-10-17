<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/styles_dashboard.css">
</head>

<body>

    <!-- Encabezado -->
    <header>
        <div class="logosec">
            <div class="logo">GeeksForGeeks</div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png" class="icn menuicn" id="menuicn" alt="menu-icon">
        </div>

        <div class="searchbar">
            <input type="text" placeholder="Search">
            <div class="searchbtn">
                <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210180758/Untitled-design-(28).png" class="icn srchicn" alt="search-icon">
            </div>
        </div>

        <div class="message">
            <div class="circle"></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183322/8.png" class="icn" alt="">
            <div class="dp">
                <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210180014/profile-removebg-preview.png" class="dpicn" alt="dp">
            </div>
        </div>
    </header>

    <div class="main-container">
        <div class="navcontainer">
            <nav class="nav">
                <div class="nav-upper-options">
                    <div class="nav-option option1"><h3> Dashboard</h3></div>
                    <div class="nav-option option2"><h3> Profile</h3></div>
                    <div class="nav-option option3"><h3> Settings</h3></div>
                    <div class="nav-option logout"><h3>Logout</h3></div>
                </div>
            </nav>
        </div>

        <div class="main">
            <div class="box-container">
                <div class="box box1"><h2>Your Stats</h2><h2>Details here</h2></div>
                <div class="box box2"><h2>Your Documents</h2><h2>Details here</h2></div>
            </div>

            <div class="report-container">
                <div class="report-header">
                    <h1 class="recent-Articles">Recent Activities</h1>
                    <button class="view">View All</button>
                </div>

                <div class="report-body">
                    <div class="report-topic-heading">
                        <h3>Activity</h3>
                        <h3>Date</h3>
                        <h3>Status</h3>
                    </div>
                    <div class="items">
                        <div class="item1"><h3>Uploaded Document</h3><h3>Today</h3><h3>Completed</h3></div>
                        <div class="item1"><h3>Updated Profile</h3><h3>Yesterday</h3><h3>Completed</h3></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="./index.js"></script>
</body>
</html>