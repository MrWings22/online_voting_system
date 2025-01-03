<?php
session_start();
ob_start();
include 'db_connection.php';  // Connect to the database
include 'headerlogn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header('Location: voterpage.php');
        exit;
    } else {
        echo "<p style='color:red; text-align: center;'>Invalid login details!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <!-- Main content container -->
    <div class="main-container">
        <!-- Login form -->
        <div class="login-container">
            <h2>User Login</h2>
            <form method="POST" action="login.php">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>

                <div class="password-toggle">
                    <input type="checkbox" id="showPassword">
                    <label for="showPassword">Show Password</label>
                </div>

                <input type="submit" value="Login">
            </form>
        </div>
    </div>

    <!-- JavaScript for password toggle -->
    <script>
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    </script>
</body>
</html>
