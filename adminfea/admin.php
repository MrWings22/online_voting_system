<?php
session_start();
include 'db_connection.php';
include 'adminfea\header.php';
// Ensure only admin can access this page
if (!isset($_SESSION['ad_username'])) {
    header("Location: adminlogin.php");
    exit; 
}

$username = $_SESSION['ad_username'];
$query = "SELECT * FROM adminlogin WHERE ad_username = '$username'";
$result = mysqli_query($conn, $query);

// Create a new election
if (isset($_POST['create_election'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $end_time = $_POST['end_time']; // Get the end time

    // Combine the end date and end time into a single datetime field
    $end_datetime = $end_date . ' ' . $end_time;

    // Insert the new election into the database with end datetime
    $query = "INSERT INTO elections (title, description, start_date, end_date)
              VALUES ('$title', '$description', '$start_date', '$end_datetime')";

    if (mysqli_query($conn, $query)) {
        echo "Election created successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Approve candidates
if (isset($_POST['approve_candidate'])) {
    $candidate_id = $_POST['candidate_id'];
    $query = "UPDATE candidate SET approved = 1 WHERE candidate_id = $candidate_id";
    
    if (mysqli_query($conn, $query)) {
        echo "Candidate approved successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete election
if (isset($_POST['delete_election'])) {
    $election_id = $_POST['election_id'];

    try {
        $query = "DELETE FROM elections WHERE election_id = $election_id";
        mysqli_query($conn, $query);
        echo "<script>alert('Election deleted successfully!');</script>";
    } catch (mysqli_sql_exception $e) {
        // Check if the error is related to foreign key constraint
        if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
            echo "<script>
                    alert('Cannot delete this election because it is linked to existing candidates or data. Please remove associated candidates before deleting the election.');
                  </script>";
        } else {
            echo "<script>alert('Error deleting election: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}

// Fetch candidates pending approval
$queryPendingCandidates = "SELECT * FROM candidate WHERE approved = 0";  // Fetch only unapproved candidates
$resultPendingCandidates = mysqli_query($conn, $queryPendingCandidates);

// Fetch all elections
$queryElections = "SELECT * FROM elections";
$resultElections = mysqli_query($conn, $queryElections);

$queryVotes = "SELECT users.fname, users.lname, elections.title
               FROM votes
               JOIN users ON votes.user_id = users.user_id
               JOIN elections ON votes.election_id = elections.election_id";
$resultVotes = mysqli_query($conn, $queryVotes);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Voting System</title>
</head>
<body>
    <h2>Admin Panel</h2>

    <!-- Section for Creating Elections -->
    <div class="admin-section">
        <h3>Create Election</h3>
        <form method="POST" action="admin.php">
            <label>Title</label>
            <input type="text" name="title" required><br>
            <label>Description</label>
            <textarea name="description" required></textarea><br>
            <label>Start Date</label>
            <input type="date" name="start_date" required><br>
            <label>End Date</label>
            <input type="date" name="end_date" required><br>
            <label>End Time</label>
            <input type="time" name="end_time" required><br> <!-- New field for selecting end time -->
            <input type="submit" name="create_election" value="Create Election">
        </form>
    </div>

    <!-- Section for Approving Candidates -->
    <div class="admin-section">
    <h3>Approve Candidates</h3>
    <?php if (mysqli_num_rows($resultPendingCandidates) > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Candidate Name</th>
                    <th>Position</th>
                    <th>Description</th>
                    <th>Backpapers If</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($candidate = mysqli_fetch_assoc($resultPendingCandidates)) { ?>
                    <tr>
                        <td><?php echo $candidate['fullname']; ?></td>
                        <td><?php echo $candidate['position']; ?></td>
                        <td><?php echo $candidate['description']; ?></td>
                        <td><?php echo $candidate['backpapers']; ?></td>
                        <td>
                            <form method="POST" action="admin.php" style="display:inline;">
                                <input type="hidden" name="candidate_id" value="<?php echo $candidate['candidate_id']; ?>">
                                <input type="submit" name="approve_candidate" value="Approve">
                            </form>
                            <form method="GET" action="candidate_details.php" style="display:inline;">
                                <input type="hidden" name="candidate_id" value="<?php echo $candidate['candidate_id']; ?>">
                                <input type="submit" value="View Details">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No candidates pending approval.</p>
    <?php } ?>
</div>


    <!-- Section for Deleting Elections -->
    <div class="admin-section">
        <h3>Manage Elections</h3>
        <?php if (mysqli_num_rows($resultElections) > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($election = mysqli_fetch_assoc($resultElections)) { ?>
                        <tr>
                            <td><?php echo $election['title']; ?></td>
                            <td><?php echo $election['description']; ?></td>
                            <td><?php echo $election['start_date']; ?></td>
                            <td><?php echo $election['end_date']; ?></td>
                            <td>
                                <form method="POST" action="admin.php">
                                    <input type="hidden" name="election_id" value="<?php echo $election['election_id']; ?>">
                                    <input type="submit" name="delete_election" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No elections available.</p>
        <?php } ?>
    </div>

 <!-- Section for Viewing Voters -->
<div class="admin-section">
    <h3>Voters List</h3>
    <?php if (mysqli_num_rows($resultVotes) > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Election</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($voter = mysqli_fetch_assoc($resultVotes)) { ?>
                    <tr>
                        <td><?php echo $voter['fname']; ?></td>
                        <td><?php echo $voter['lname']; ?></td>
                        <td><?php echo $voter['title']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No voters have cast their votes yet.</p>
    <?php } ?>
</div>

    <p><a href="admin_stat.php">Statistics</a></p>
    <p><a href="admin_candida.php">candidates list</a>
    <p><a href="results1.php">result</a>

<?php include 'footerall.php'; ?>
</body>
</html>
