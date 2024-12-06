<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Election Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        .result-section {
            margin: 20px 0;
        }
        .no-results {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Election Results for <?php echo htmlspecialchars($fullname); ?></h1>

    <?php 
    // Check if the election has ended
    if ($position_winners) {
        echo "<h3>Your Position: " . htmlspecialchars($position) . "</h3>";
        echo "<p>You have received " . $votes . " votes.</p>";
        
        echo "<h3>All Candidates in " . htmlspecialchars($position) . "</h3>";
        
        // Display all candidates' votes in the same position
        while ($winner = $position_winners->fetch_assoc()) {
            echo "<p>" . $winner['fullname'] . " with " . $winner['votes'] . " votes</p>";
        }
    } else {
        echo "<p class='no-results'>Election results are not available yet. The election might still be ongoing.</p>";
    }
    ?>
<?php
// Database connection
include 'db_connection.php';

// Get the current date
$current_date = date("Y-m-d");

// Fetch the candidate's details from the database
$candidate_query = $conn->query("SELECT fullname, position FROM candidate WHERE candidate_id = '$candidate_id' LIMIT 1");
$candidate = $candidate_query->fetch_assoc();

// If the candidate exists
if ($candidate) {
    $position = $candidate['position'];
    $fullname = $candidate['fullname'];

    // Get the votes for the candidate in their respective position
    $votes_query = $conn->query("SELECT votes FROM candidate WHERE candidate_id = '$candidate_id' LIMIT 1");
    $votes = $votes_query->fetch_assoc()['votes'];

    // Check if the election has ended
    $election_status = $conn->query("SELECT end_date FROM elections WHERE end_date <= '$current_date' LIMIT 1");
    
    // If the election has ended
    if ($election_status && $election_status->num_rows > 0) {
        // Get all candidates' votes in the same position
        $position_winners = $conn->query("SELECT fullname, votes FROM candidate WHERE position = '$position' ORDER BY votes DESC");
    } else {
        $position_winners = null;
    }
} else {
    // Candidate not found
    echo "Candidate not found.";
    exit;
}
?>
</body>
</html>
