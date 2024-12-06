<?php
session_start();
include 'db_connection.php';
include 'header.php'; 

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch the user details from the users table
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Fetch ongoing elections from the elections table
$queryElections = "SELECT * FROM elections WHERE CURDATE() BETWEEN start_date AND end_date";
$resultElections = mysqli_query($conn, $queryElections);

// Fetch elections that have ended
$queryPastElections = "SELECT * FROM elections WHERE CURDATE() > end_date";
$resultPastElections = mysqli_query($conn, $queryPastElections);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vote - Online Voting System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="voterpage.css">
</head>
<body>
    <h2>Welcome, <?php echo $user['fname']; ?>!</h2>
    <div class="container container-left">  
    <!-- Profile Overview -->
    <div class="container container-left">
    <h3>Profile Overview</h3>
    <ul>
    <li><strong><i class="fas fa-user"></i> Full Name:</strong> <?php echo $user['fname'] . ' ' . $user['lname']; ?></li>
    <li><strong><i class="fas fa-user-circle"></i> Username:</strong> <?php echo $user['username']; ?></li>
    <li><strong><i class="fas fa-building"></i> Department:</strong> <?php echo empty($user['department']) ? 'Not selected yet' : $user['department']; ?></li>
    <li><strong><i class="fas fa-calendar-alt"></i> Year:</strong> <?php echo empty($user['year']) ? 'Not selected yet' : $user['year']; ?></li>
    <li><strong><i class="fas fa-users"></i> Batch:</strong> <?php echo empty($user['batch']) ? 'Not selected yet' : $user['batch']; ?></li>
    </ul>
    </div>
<div class="container container-left">
<h3><i class="fas fa-vote-yea"></i> Voting Options</h3>
    <form method="POST" action="votingpage.php">
        <br><br>     
            <h4>Select Your Department, Batch, and Year</h4>
            <!-- Department Selection -->
            <label for="department">Department:</label>
            <select name="department" id="department" required>
                <option value="">--Select Department--</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Social Work">Social Work</option>
                <option value="Commerce">Commerce</option>
                <option value="Media">Media</option>
                <!-- Add more departments as needed -->
            </select>

            <!-- Year Selection -->
            <label for="year">Year:</label>
            <select name="year" id="year" required>
                <option value="">--Select Year--</option>
                <option value="1st">1st Year</option>
                <option value="2nd">2nd Year</option>
                <option value="3rd">3rd Year</option>
                <option value="4th">4th Year</option>
            </select>

            <!-- Batch Selection -->
            <label for="batch">Batch:</label>
            <select name="batch" id="batch">
                <option value="">--Select Batch--</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <!-- Add more batches as needed -->
            </select>     

        <br><br>
        <?php if (mysqli_num_rows($resultElections) > 0) { ?>
            <label for="election">Select an Ongoing Election:</label>
            <select name="election_id" id="election" required>
                <option value="">--Select Election--</option>
                <?php while ($election = mysqli_fetch_assoc($resultElections)) { ?>
                    <option value="<?php echo $election['election_id']; ?>">
                        <?php echo $election['title']; ?> (Ends on: <?php echo $election['end_date']; ?>)
                    </option>
                <?php } ?>
            </select>
            <br><br>
            <input type="submit" value="Proceed to Voting">
        <?php } else { ?>
            <p>No ongoing elections at the moment.</p>
        <?php } ?>
        </div>
        <br><br>

        <!-- Option to View Results and Show ended elections -->
        <div class="container container-left">
        <h3>View Election Results</h3>
        <?php if (mysqli_num_rows($resultPastElections) > 0) { ?>
            <p>Past Elections:</p>
            <ul>
                <?php while ($pastElection = mysqli_fetch_assoc($resultPastElections)) { ?>
                    <li>
                        <?php echo $pastElection['title']; ?> (Ended on: <?php echo $pastElection['end_date']; ?>)
                        - <a href="results.php?election_id=<?php echo $pastElection['election_id']; ?>">View Results</a>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No past elections available for result viewing.</p>
        <?php } ?>
        </div>
        </div>
    </form>
    <?php include 'footerall.php'; ?>

</body>
</html>
