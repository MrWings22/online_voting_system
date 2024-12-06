<?php
// Database connection
include 'db_connection.php';

// Fetch the winners for each position (the candidate with the highest votes)
$winner_main_union = $conn->query("SELECT fullname, votes FROM candidate WHERE position = 'Main Union' ORDER BY votes DESC LIMIT 1")->fetch_assoc(); 
$winner_dept_rep = $conn->query("SELECT fullname, votes FROM candidate WHERE position = 'Department Representative' ORDER BY votes DESC LIMIT 1")->fetch_assoc(); 
$winner_class_rep = $conn->query("SELECT fullname, votes FROM candidate WHERE position = 'Class Representative' ORDER BY votes DESC LIMIT 1")->fetch_assoc(); 

// Check for errors
if (!$winner_main_union) {
    echo "Error fetching Main Union winner: " . $conn->error;
}
if (!$winner_dept_rep) {
    echo "Error fetching Department Representative winner: " . $conn->error;
}
if (!$winner_class_rep) {
    echo "Error fetching Class Representative winner: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results</title>
</head>
<body>
    <h1>Election Results</h1>

    <h3>Main Union Winner</h3>
    <p>
        <?php 
        // Check if votes exist
        echo $winner_main_union['fullname'] . " with " . ($winner_main_union['votes'] !== null ? $winner_main_union['votes'] : '0') . " votes"; 
        ?>
    </p>

    <h3>Department Representative Winner</h3>
    <p>
        <?php 
        echo $winner_dept_rep['fullname'] . " with " . ($winner_dept_rep['votes'] !== null ? $winner_dept_rep['votes'] : '0') . " votes"; 
        ?>
    </p>

    <h3>Class Representative Winner</h3>
    <p>
        <?php 
        echo $winner_class_rep['fullname'] . " with " . ($winner_class_rep['votes'] !== null ? $winner_class_rep['votes'] : '0') . " votes"; 
        ?>
    </p>
</body>
</html>
