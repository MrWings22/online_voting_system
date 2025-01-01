<?php
session_start();
include 'db_connection.php';  // Connect to the database
require_once 'header_back.php';
// Render the header with the appropriate back link
renderHeader('index.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['ad_username'];
    $password = $_POST['ad_password'];

    $query = "SELECT * FROM adminlogin WHERE ad_username = '$username' AND ad_password = '$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['ad_username'] = $username;
        header('Location: admin.php');
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
    <link rel="stylesheet" href="adminlogin.css">
</head>
<body>

<div class="container">
    <form action="adminlogin.php" method="POST">
        <h2><i class="fas fa-lock icon"></i>Admin Login</h2>
        <label for="ad_username"><i class="fas fa-user icon"></i>Username</label>
        <input type="text" id="ad_username" name="ad_username" placeholder="Enter your username" required>
        <label for="ad_password"><i class="fas fa-key icon"></i>Password</label>
        <input type="password" id="ad_password" name="ad_password" placeholder="Enter your password" required>
        <div class="checkbox-container">
        <input type="checkbox" id="showPassword">
        <label for="showPassword">Show Password</label>
        </div>

        <input type="submit" value="Login">
    </form>
</div>

    <script>
        // JavaScript to toggle password visibility
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordInput = document.getElementById('ad_password');
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    </script>
</body>
</html>
