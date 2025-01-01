<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Header</title>
    <link rel="stylesheet" href="header.css">
</head>
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
        <a href="javascript:void(0)" class="close-btn" onclick="closeDrawer()">&times;</a><br><br>
        <a href="adminlogin.php"><i class="fas fa-user-shield"></i> Admin Login</a>
        <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
        <a href="help.php"><i class="fas fa-info-circle"></i> Help</a>

    </div>

    <script>
        function openDrawer() {
            document.getElementById("sideDrawer").style.width = "250px";
        }

        function closeDrawer() {
            document.getElementById("sideDrawer").style.width = "0";
        }
        function toggleMenu() {
    const menuIcon = document.querySelector('.menu-icon');
    menuIcon.classList.toggle('active');
    openDrawer();
}

    </script>
</body>
</body>
</html>