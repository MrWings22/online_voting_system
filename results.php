<?php
// Database connection
include 'db_connection.php';
require_once 'header_back.php';
renderHeader('voterpage.php');

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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Results CSS -->
    <link rel="stylesheet" href="results.css">
</head>
<body>

    <div class="container">
        <h1>Election Results</h1>

        <!-- Main Union Winner -->
        <div class="result-section">
            <h3>Main Union Winner</h3>
            <p>
                <?php 
                // Check if votes exist
                if ($winner_main_union) {
                    echo "<div class='winner'>" . $winner_main_union['fullname'] . " with " . ($winner_main_union['votes'] !== null ? $winner_main_union['votes'] : '0') . " votes</div>";
                } else {
                    echo "<p class='no-results'>No results available yet.</p>";
                }
                ?>
            </p>
        </div>

        <!-- Department Representative Winner -->
        <div class="result-section">
            <h3>Department Representative Winner</h3>
            <p>
                <?php 
                if ($winner_dept_rep) {
                    echo "<div class='winner'>" . $winner_dept_rep['fullname'] . " with " . ($winner_dept_rep['votes'] !== null ? $winner_dept_rep['votes'] : '0') . " votes</div>";
                } else {
                    echo "<p class='no-results'>No results available yet.</p>";
                }
                ?>
            </p>
        </div>

        <!-- Class Representative Winner -->
        <div class="result-section">
            <h3>Class Representative Winner</h3>
            <p>
                <?php 
                if ($winner_class_rep) {
                    echo "<div class='winner'>" . $winner_class_rep['fullname'] . " with " . ($winner_class_rep['votes'] !== null ? $winner_class_rep['votes'] : '0') . " votes</div>";
                } else {
                    echo "<p class='no-results'>No results available yet.</p>";
                }
                ?>
            </p>
        </div>
        
        <a href="javascript:history.back()" > Back to Previous Page</a>

    </div>

<?php include 'footerall.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
