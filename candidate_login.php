<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $admission_no = $_POST['admission_no'];

    // Validate login credentials based on username and admission number
    $query = "SELECT * FROM candidate WHERE username = '$username' AND admission_no = '$admission_no'"; // Corrected table name
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Set session with candidate ID
        $row = mysqli_fetch_assoc($result);
        $_SESSION['candidate_id'] = $row['candidate_id'];
        header("Location: candidate_dash.php");
    } else {
        echo "Invalid username or admission number!";
    }
}
require_once 'header_back.php';
renderHeader('login.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Login</title>
    <link rel="stylesheet" href="candidate_login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- FontAwesome Icons -->
</head>
<body>

    <h2>Candidate Login</h2>
    <form action="candidate_login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required placeholder="Enter your username"><br>

        <label for="admission_no">Admission Number:</label>
        <input type="text" name="admission_no" id="admission_no" required placeholder="Enter your admission number"><br>

        <input type="submit" value="Login">
    </form>

    <?php include 'footerall.php'; ?>
</body>
</html>
