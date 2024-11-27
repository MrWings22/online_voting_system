<?php
session_start();
include 'db_connection.php';  // Connect to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['ad_username'];
    $password = $_POST['ad_password'];

    $query = "SELECT * FROM adminlogin WHERE ad_username = '$username' AND ad_password = '$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['ad_username'] = $username;
        header('Location: sampleadmin.php');
    } else {
        echo "Invalid login details!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Online Voting System</title>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <h1>Admin Login - Online Voting System</h1>
    </div>

    <!-- Admin Login Form -->
    <div class="container">
        <h2>Admin Login</h2>
        <form action="adminlogin.php" method="POST">
            <label for="ad_username">Username</label>
            <input type="text" id="ad_username" name="ad_username" required>
            <label for="ad_password">Password</label>
            <input type="password" id="ad_password" name="ad_password" required>
            <input type="checkbox" id="showPassword"> 
            <label for="showPassword">Show Password</label>
            <input type="submit" value="Login">
        </form>
    </div>

    <script>
        // JavaScript to toggle password visibility
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordInput = document.getElementById('ad_password');
            if (this.checked) {
                passwordInput.type = 'text'; // Show password
            } else {
                passwordInput.type = 'password'; // Hide password
            }
        });
    </script>
  <?php include 'footerall.php'; ?>
</body>
</html>
