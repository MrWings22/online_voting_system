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
$queryElections = "SELECT * FROM elections WHERE NOW() BETWEEN start_date AND end_date";
$resultElections = mysqli_query($conn, $queryElections);

// Fetch elections that have ended but results are not yet available
$queryPendingResults = "SELECT * FROM elections 
                        WHERE NOW() > end_date AND NOW() < result_publish_time";
$resultPendingResults = mysqli_query($conn, $queryPendingResults);

// Fetch elections whose results are available
$queryPastElections = "SELECT * FROM elections WHERE NOW() >= result_publish_time";
$resultPastElections = mysqli_query($conn, $queryPastElections);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vote - Online Voting System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="voterpage.css">
</head>
<body>
    <h2>Welcome, <?php echo $user['fname'].' '.$user['lname']; ?>!</h2>
    <div class="container">
        <!-- Profile Overview -->
        <div class="profile-container">
            <h3>Profile Overview</h3>
            <ul>
                <li><strong><i class="fas fa-user"></i> Full Name:</strong> <?php echo $user['fname'] . ' ' . $user['lname']; ?></li>
                <li><strong><i class="fas fa-user-circle"></i> Username:</strong> <?php echo $user['username']; ?></li>
                <li><strong><i class="fas fa-building"></i> Department:</strong> <?php echo empty($user['department']) ? 'Not selected yet' : $user['department']; ?></li>
                <li><strong><i class="fas fa-calendar-alt"></i> Year:</strong> <?php echo empty($user['year']) ? 'Not selected yet' : $user['year']; ?></li>
                <li><strong><i class="fas fa-users"></i> Batch:</strong> <?php echo empty($user['batch']) ? 'Not selected yet' : $user['batch']; ?></li>
            </ul>
        </div>

        <!-- Voting Options -->
        <div class="voting-container">
            <h3><i class="fas fa-vote-yea"></i> Voting Options</h3>
            <form method="POST" action="votingpage.php">
                <p><strong>Department:</strong> <?php echo $user['department']; ?></p>
                <p><strong>Year:</strong> <?php echo $user['year']; ?></p>
                <p><strong>Batch:</strong> <?php echo $user['batch']; ?></p>

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
            </form>
        </div>

        <!-- Past Election Results -->
        <div class="results-container">
            <h3 class="results-title"><i class="fas fa-chart-bar"></i> Election Results</h3>

            <?php if (mysqli_num_rows($resultPastElections) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Election Title</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($pastElection = mysqli_fetch_assoc($resultPastElections)): ?>
                                <tr>
                                    <td><?php echo $pastElection['title']; ?></td>
                                    <td><?php echo $pastElection['end_date']; ?></td>
                                    <td>
                                        <a href="results.php?election_id=<?php echo $pastElection['election_id']; ?>" 
                                           class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View Results
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif (mysqli_num_rows($resultPendingResults) > 0): ?>
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-info-circle"></i> Results will be published soon for the following elections:
                    <ul>
                        <?php while ($pendingResult = mysqli_fetch_assoc($resultPendingResults)): ?>
                            <li>
                                <strong><?php echo $pendingResult['title']; ?></strong> 
                                (Results will be published on: <?php echo $pendingResult['result_publish_time']; ?>)
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-info-circle"></i> No past elections available for result viewing.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
