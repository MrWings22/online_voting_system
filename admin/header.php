<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Header</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    <a href="javascript:void(0)" class="close-btn" onclick="closeDrawer()">&times;</a>
    <a href="index.php"><i class="fas fa-arrow-left"></i> Back</a>
    <a href="candidateregister.php"><i class="fas fa-user-plus"></i> Candidate Register</a>
    <a href="candidate_login.php"><i class="fas fa-sign-in-alt"></i> Candidate Login</a>
    <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
    <a href="logout1.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
