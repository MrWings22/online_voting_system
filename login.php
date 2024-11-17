<?php
session_start();
ob_start();
include 'db_connection.php';  // Connect to the database
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header('Location: voterpage.php');
        exit; // Added exit after header redirect to stop further script execution
    } else {
        echo "<p style='color:red;'>Invalid login details!</p>"; // Improved error message display
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>


</head>
<body>
    <div class="login-container">
        <h2>User Login</h2>
        <form method="POST" action="login.php">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <input type="checkbox" id="showPassword"> 
            <label for="showPassword">Show Password</label>
            <input type="submit" value="Login">
        </form>
    </div>

    <script>
        // JavaScript to toggle password visibility
        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordInput = document.getElementById('password');
            if (this.checked) {
                passwordInput.type = 'text'; // Show password
            } else {
                passwordInput.type = 'password'; // Hide password
            }
        });
    </script>
<?php ob_end_flush(); ?>

<?php include 'footerall.php'; ?>
</body>
</html>
