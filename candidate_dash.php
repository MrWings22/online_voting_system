<?php
session_start();
include 'db_connection.php';
require_once 'header_back.php';

// Render the header with the appropriate back link
renderHeader('candidate_login.php');

// Check if candidate is logged in
if (!isset($_SESSION['candidate_id'])) {
    header("Location: candidate_login.php");
    exit();
}

$candidate_id = $_SESSION['candidate_id'];

// Fetch candidate details along with election title
$query = "
    SELECT c.*, e.title AS election_title 
    FROM candidate c 
    LEFT JOIN elections e ON c.election = e.election_id 
    WHERE c.candidate_id = $candidate_id
";
$result = mysqli_query($conn, $query);
$candidate = mysqli_fetch_assoc($result);

// Fetch elections that have ended
$queryPastElections = "SELECT * FROM elections WHERE NOW() > end_date";
$resultPastElections = mysqli_query($conn, $queryPastElections);
// Fetch available elections
$elections_query = "SELECT * FROM elections";
$elections_result = mysqli_query($conn, $elections_query);

// If the form is submitted to update details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // form handling code here
}

$is_editing = isset($_POST['edit']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Dashboard</title>
<!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="candidate_dash.css">
</head>
<body>
    <h2>Welcome, <?php echo $candidate['fullname']; ?></h2>

    <!-- Candidate details -->
    <h3>Your Details</h3>
    <form action="candidate_dashedit.php" method="POST" enctype="multipart/form-data">
        <table class="candidate-form">
            <tr>
                <td><label for="fullname"><i class="fas fa-user"></i> Full Name:</label></td>
                <td>
                    <?php if ($is_editing): ?>
                        <input type="text" name="fullname" value="<?php echo $candidate['fullname']; ?>" required>
                    <?php else: ?>
                        <span><?php echo $candidate['fullname']; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><label for="year"><i class="fas fa-calendar-alt"></i> Year of Study:</label></td>
                <td>
                    <?php if ($is_editing): ?>
                        <input type="text" name="year" value="<?php echo $candidate['year']; ?>" required>
                    <?php else: ?>
                        <span><?php echo $candidate['year']; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><label for="batch"><i class="fas fa-graduation-cap"></i> Batch:</label></td>
                <td>
                    <?php if ($is_editing): ?>
                        <input type="text" name="batch" value="<?php echo $candidate['batch']; ?>" required>
                    <?php else: ?>
                        <span><?php echo $candidate['batch']; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><label for="department"><i class="fas fa-building"></i> Department:</label></td>
                <td>
                    <?php if ($is_editing): ?>
                        <input type="text" name="department" value="<?php echo $candidate['department']; ?>" required>
                    <?php else: ?>
                        <span><?php echo $candidate['department']; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><label for="election"><i class="fas fa-vote-yea"></i> Select Election:</label></td>
                <td>
                    <?php if ($is_editing): ?>
                        <select name="election" required>
                            <?php while ($row = mysqli_fetch_assoc($elections_result)) { ?>
                                <option value="<?php echo $row['election_id']; ?>" 
                                    <?php if ($candidate['election'] == $row['election_id']) echo 'selected'; ?>>
                                    <?php echo $row['title']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php else: ?>
                        <span><?php echo $candidate['election_title']; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><label for="position"><i class="fas fa-briefcase"></i> Position:</label></td>
                <td>
                    <?php if ($is_editing): ?>
                        <input type="text" name="position" value="<?php echo $candidate['position']; ?>" required>
                    <?php else: ?>
                        <span><?php echo $candidate['position']; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><label for="description"><i class="fas fa-pencil-alt"></i> Description:</label></td>
                <td>
                    <?php if ($is_editing): ?>
                        <textarea name="description" required><?php echo $candidate['description']; ?></textarea>
                    <?php else: ?>
                        <span><?php echo $candidate['description']; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><label for="photo"><i class="fas fa-camera"></i> Photo:</label></td>
                <td>
                    <?php if ($is_editing): ?>
                        <input type="file" name="photo">
                        <?php if (!empty($candidate['photo'])): ?>
                            <img src="<?php echo $candidate['photo']; ?>" alt="Candidate Photo" style="max-width: 200px; max-height: 200px;">
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if (!empty($candidate['photo'])): ?>
                            <img src="<?php echo $candidate['photo']; ?>" alt="Candidate Photo" style="max-width: 200px; max-height: 200px;">
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

    <!-- If editing, show the update button -->
    <?php if ($is_editing): ?>
        <input type="hidden" name="update" value="true">
        <input type="submit" value="Update Details" class="btn-submit">
    <?php else: ?>
        <input type="hidden" name="edit" value="true">
        <input type="submit" value="Edit" class="btn-edit">
    <?php endif; ?>
    <br><a href="logout.php">Logout</a>
</form>


    <!-- Option to View Results and Show ended elections -->
<h3>Election Results</h3>
<form>
    <table class="candidate-form">
        <tr>
            <td><label><i class="fas fa-calendar-check"></i>Elections:</label></td>
            <td>
                <?php if (mysqli_num_rows($resultPastElections) > 0): ?>
                    <table class="table table-bordered table-striped">
                        <thead>
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
                                        <a href="result_forcandidate.php?election_id=<?php echo $pastElection['election_id']; ?>" 
                                           class="btn btn-primary btn-sm">
                                           <i class="fas fa-eye"></i> View Results
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-info-circle"></i> No Results Published Yet.
                    </div>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>
</body>
</html>
<?php
// Close the database connection
mysqli_close($conn);
?>
