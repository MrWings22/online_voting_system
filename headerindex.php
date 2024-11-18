<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System-Welcome</title>
    <link rel="stylesheet" href="header.css">
<body>
    <header>
        <div class="header-container">
            <!-- College logo -->
            <div class="logo">
                <img src="uploads/collegelogo.png" alt="College Logo">
            </div>
            <!-- College name -->
            <div class="college-name">
                <h1>BVM Holy Cross College Cherpunkal</h1>
            </div>
        </div>
           
        <div class="voting-system-text">
            Online Voting System
        </div>

        <!-- Three-line side menu icon -->
        <div class="menu-icon" onclick="openDrawer()">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </header>

    <!-- Hidden side drawer -->
    <div id="sideDrawer" class="side-drawer">
        <a href="javascript:void(0)" class="close-btn" onclick="closeDrawer()">&times;</a>
        <a href="adminlogin.php">Admin Login</a>
        <a href="about.php">About</a>
    </div>

    <script>
        function openDrawer() {
            document.getElementById("sideDrawer").style.width = "250px";
        }

        function closeDrawer() {
            document.getElementById("sideDrawer").style.width = "0";
        }
    </script>
</body>
</html>
