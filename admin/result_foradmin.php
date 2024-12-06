<?php
include 'db_connection.php';
require_once 'header_back.php';
renderHeader('admin.php');

// Get the current date
$current_date = date("Y-m-d");

// Check if the election has ended
$election_status = $conn->query("SELECT end_date FROM elections WHERE end_date <= '$current_date' LIMIT 1");

// If the election has ended, fetch the winners
if ($election_status && $election_status->num_rows > 0) {
    $winners_main_union = $conn->query("SELECT fullname, votes FROM candidate WHERE position = 'Main Union' ORDER BY votes DESC")->fetch_all(MYSQLI_ASSOC);
    $winners_dept_rep = $conn->query("SELECT fullname, votes, department FROM candidate WHERE position = 'Department Representative' ORDER BY votes DESC")->fetch_all(MYSQLI_ASSOC);
    $winners_class_rep = $conn->query("SELECT fullname, votes, department, batch FROM candidate WHERE position = 'Class Representative' ORDER BY votes DESC")->fetch_all(MYSQLI_ASSOC);
} else {
    $winners_main_union = null;
    $winners_dept_rep = null;
    $winners_class_rep = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results - Admin</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 30px;
        }
        .result-section {
            margin: 20px 0;
        }
        h3 {
            color: #333;
            text-align: left;
            border-bottom: 2px solid #007BFF;
            display: inline-block;
            padding-bottom: 5px;
        }
        ul {
            list-style: none;
            padding: 0;
            text-align: left;
        }
        ul li {
            background: #f8f9fa;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        ul li:hover {
            background: #e9ecef;
        }
        .no-results {
            color: red;
            font-size: 1.2em;
        }
        .download-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .download-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Election Results</h1>

        <?php 
        if (!$winners_main_union && !$winners_dept_rep && !$winners_class_rep) {
            echo "<p class='no-results'>No results published yet. The election might still be ongoing.</p>";
        } else { 
        ?>

        <!-- Main Union Winners -->
        <div class="result-section">
            <h3>Main Union Winners</h3>
            <?php if ($winners_main_union) { ?>
                <ul>
                    <?php foreach ($winners_main_union as $winner) { ?>
                        <li><?php echo $winner['fullname'] . " - " . $winner['votes'] . " votes"; ?></li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p class="no-results">No winner declared yet for Main Union.</p>
            <?php } ?>
        </div>

        <!-- Department Representative Winners -->
        <div class="result-section">
            <h3>Department Representative Winners</h3>
            <?php if ($winners_dept_rep) { ?>
                <ul>
                    <?php foreach ($winners_dept_rep as $winner) { ?>
                        <li><?php echo $winner['fullname'] . " (" . $winner['department'] . ") - " . $winner['votes'] . " votes"; ?></li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p class="no-results">No winner declared yet for Department Representative.</p>
            <?php } ?>
        </div>

        <!-- Class Representative Winners -->
        <div class="result-section">
            <h3>Class Representative Winners</h3>
            <?php if ($winners_class_rep) { ?>
                <ul>
                    <?php foreach ($winners_class_rep as $winner) { ?>
                        <li><?php echo $winner['fullname'] . " (" . $winner['department'] . " - " . $winner['batch'] . " Batch) - " . $winner['votes'] . " votes"; ?></li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <p class="no-results">No winner declared yet for Class Representative.</p>
            <?php } ?>
        </div>

        <!-- Download Button -->
        <form action="download_results.php" method="POST">
            <input type="submit" name="download" value="Download Results as PDF" class="download-btn" />
        </form>

        <?php } ?>
    </div>
<?php
include 'footerall.php';
?>
</body>
</html>
