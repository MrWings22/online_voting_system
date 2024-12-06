<?php
session_start();
include 'db_connection.php';
require_once 'header_back.php';
renderHeader('voterpage.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Clear session variables related to voting
unset($_SESSION['election_id']);
unset($_SESSION['department']);
unset($_SESSION['year']);
unset($_SESSION['batch']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success - Vote Submitted</title>
    <style>
        .success-containerr {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            background-color: #f9f9f9;
        }
        .success-box {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }
        .success-box h2 {
            color: #28a745; /* Green color for success */
            margin-bottom: 20px;
        }
        .success-box p {
            font-size: 18px;
            color: #333;
        }
        .success-box a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .success-box a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="success-containerr">
    <div class="success-box">
        <h2>Vote Submitted Successfully!</h2>
        <p>Thank you for participating in the election. Your vote has been recorded successfully.</p>
        <p>If you have any questions or need further assistance, please contact the election committee.</p>
        <a href="voterpage.php">Go Back to Home</a>
    </div>
</div>

</body>
</html>
