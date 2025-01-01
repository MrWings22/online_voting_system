<?php
session_start();
include 'db_connection.php';
require_once 'header_back.php';
renderHeader('login.php'); // Include the header template here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $admission_no = $_POST['admission_no'];

    // Validate login credentials based on username and admission number
    $query = "SELECT * FROM candidate WHERE username = '$username' AND admission_no = '$admission_no'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Set session with candidate ID
        $row = mysqli_fetch_assoc($result);
        $_SESSION['candidate_id'] = $row['candidate_id'];
        header("Location: candidate_dash.php");
        exit; // Ensure no further execution after the redirect
    } else {
        echo "<p style='color: red; text-align: center;'>Invalid username or admission number!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Login</title>
    <link rel="stylesheet" href="candidate_login.css">
</head>
<body>


    <!-- Content Area for Login Form -->
    <div class="content">
        <div class="login-container">
            <h2>Candidate Login</h2>
            <form action="candidate_login.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required placeholder="Enter your username">

                <label for="admission_no">Admission Number:</label>
                <input type="text" name="admission_no" id="admission_no" required placeholder="Enter your admission number">

                <input type="submit" value="Login">
            </form>
        </div>
    </div>
</body>
</html>
