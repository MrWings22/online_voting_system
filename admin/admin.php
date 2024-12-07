<?php
session_start();
include 'db_connection.php';
require_once 'header_back.php';

// Render the header with the appropriate back link
renderHeader('index.php');

// Ensure only admin can access this page
$username = $_SESSION['ad_username'];
$query = "SELECT * FROM adminlogin WHERE ad_username = '$username'";
$result = mysqli_query($conn, $query);

// Handle sections based on `section` query parameter
$section = isset($_GET['section']) ? $_GET['section'] : '';

// Create a new election
if (isset($_POST['create_election'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $end_time = $_POST['end_time'];

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
    $query = "DELETE FROM elections WHERE election_id = $election_id";

    if (mysqli_query($conn, $query)) {
        echo "Election deleted successfully!";
    } else {
        echo "Error deleting election: " . mysqli_error($conn);
    }
}

// Fetch candidates pending approval
$queryPendingCandidates = "SELECT * FROM candidate WHERE approved = 0"; 
$resultPendingCandidates = mysqli_query($conn, $queryPendingCandidates);

// Fetch all elections for manage elections section
$queryAllElections = "SELECT * FROM elections";
$resultAllElections = mysqli_query($conn, $queryAllElections);

// Fetch voters data for view voters section
$queryVoters = "SELECT users.fname, users.lname, elections.title 
                FROM votes
                JOIN users ON votes.user_id = users.user_id
                JOIN elections ON votes.election_id = elections.election_id";
$resultVoters = mysqli_query($conn, $queryVoters);

// Query to count the number of positions a voter has voted for across categories
$queryVoters = "
    SELECT u.fname, u.lname, u.department, COUNT(v.category) AS vote_count
    FROM users u
    LEFT JOIN votes v ON u.user_id = v.user_id
    GROUP BY u.user_id";
$resultVoters = mysqli_query($conn, $queryVoters);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="admin.php?section=create_election">Create Election</a></li>
                <li class="nav-item"><a class="nav-link" href="admin.php?section=approve_candidates">Approve Candidates</a></li>
                <li class="nav-item"><a class="nav-link" href="admin.php?section=manage_elections">Manage Elections</a></li>
                <li class="nav-item"><a class="nav-link" href="admin.php?section=view_voters">View Voters</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_stat.php"><i class="fas fa-chart-bar"></i> Statistics</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_candida.php"><i class="fas fa-list-alt"></i> Candidates List</a></li>
                <li class="nav-item"><a class="nav-link" href="result_foradmin.php"><i class="fas fa-poll"></i> Results</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_view_messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
            </ul>
            <span class="navbar-text">Welcome, Admin</span>
            <a href="logoutad.php" class="btn btn-danger ml-3">Logout</a>
        </div>
    </nav>

    <!-- Content Section -->
    <div class="container mt-4">
        <?php if ($section == '') { ?>
            <!-- Default Welcome Message -->
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h5 class="card-title">Welcome to the Admin Panel</h5>
                    <p>Choose an option from the menu to manage elections, candidates, and other tasks.</p>
                    <img src="uploads/collegeimage.png" class="img-fluid" alt="Welcome Image">
                </div>
            </div>
        <?php } elseif ($section == 'create_election') { ?>
<!-- Create Election Section -->
<div class="card bg-secondary text-white">
    <div class="card-body">
        <h5 class="card-title">Create Election</h5>
        <form method="POST" action="admin.php?section=create_election">
            <div class="form-group mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>
            <div class="form-group mb-3">
                <label>Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Start Time</label>
                <input type="time" name="start_time" id="start_time" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>End Time</label>
                <input type="time" name="end_time" id="end_time" class="form-control" required>
            </div>
            <button type="submit" name="create_election" class="btn btn-primary">Create Election</button>
        </form>
    </div>
</div>
        <?php } elseif ($section == 'approve_candidates') { ?>
            <!-- Approve Candidates Section -->
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h5 class="card-title">Approve Candidates</h5>
                    <?php if (mysqli_num_rows($resultPendingCandidates) > 0) { ?>
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($candidate = mysqli_fetch_assoc($resultPendingCandidates)) { ?>
                                    <tr>
                                        <td><?php echo $candidate['fullname']; ?></td>
                                        <td><?php echo $candidate['position']; ?></td>
                                        <td><?php echo $candidate['description']; ?></td>
                                        <td>
                                            <form method="POST" action="admin.php?section=approve_candidates" class="d-inline">
                                                <input type="hidden" name="candidate_id" value="<?php echo $candidate['candidate_id']; ?>">
                                                <button type="submit" name="approve_candidate" class="btn btn-success">Approve</button>
                                            </form>
                                            <a href="candidate_details.php?candidate_id=<?php echo $candidate['candidate_id']; ?>" class="btn btn-info">View Details</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p>No candidates pending approval.</p>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="container mt-4">
    <?php if ($section == 'manage_elections') { ?>
        <!-- Manage Elections Section -->
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <h5 class="card-title">Manage Elections</h5>
                <?php if (mysqli_num_rows($resultAllElections) > 0) { ?>
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($election = mysqli_fetch_assoc($resultAllElections)) { ?>
                                <tr>
                                    <td><?php echo $election['title']; ?></td>
                                    <td><?php echo $election['description']; ?></td>
                                    <td><?php echo $election['start_date']; ?></td>
                                    <td><?php echo $election['end_date']; ?></td>
                                    <td>
                                        <form method="POST" action="admin.php?section=manage_elections" class="d-inline">
                                            <input type="hidden" name="election_id" value="<?php echo $election['election_id']; ?>">
                                            <button type="submit" name="delete_election" class="btn btn-danger">Delete</button>
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
        </div>
    <?php } elseif ($section == 'view_voters') { ?>
<!-- View Voters Section -->
<div class="card bg-secondary text-white">
    <div class="card-body">
        <h5 class="card-title">View Voters</h5>
        <?php if (mysqli_num_rows($resultVoters) > 0) { ?>
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Department</th>
                        <th>Number of Positions Voted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($voter = mysqli_fetch_assoc($resultVoters)) { ?>
                        <tr>
                            <td><?php echo $voter['fname']; ?></td>
                            <td><?php echo $voter['lname']; ?></td>
                            <td><?php echo $voter['department']; ?></td>
                            <td><?php echo $voter['vote_count']; ?></td> <!-- Show the number of positions voted -->
                        </tr>
                    <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No voter data available.</p>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php
    include 'footerall.php';
?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   <script>
    // JavaScript to enforce strict date and time restrictions
    document.addEventListener("DOMContentLoaded", function () {
        const now = new Date();

        // Format current date as YYYY-MM-DD
        const today = now.toISOString().split('T')[0];

        // Format current time as HH:MM
        const currentTime = now.toTimeString().slice(0, 5);

        // Get input fields
        const startDate = document.getElementById('start_date');
        const startTime = document.getElementById('start_time');
        const endDate = document.getElementById('end_date');
        const endTime = document.getElementById('end_time');

        // Set minimum value for Start Date
        startDate.setAttribute('min', today);

        // Set minimum value for Start Time when Start Date is today
        startDate.addEventListener('change', function () {
            if (startDate.value === today) {
                startTime.setAttribute('min', currentTime);
            } else {
                startTime.removeAttribute('min');
            }
        });

        // Dynamically update minimum value for End Date
        startDate.addEventListener('change', function () {
            endDate.setAttribute('min', startDate.value);
        });

        // Dynamically update minimum value for End Time
        startTime.addEventListener('input', function () {
            if (endDate.value === startDate.value) {
                endTime.setAttribute('min', startTime.value || currentTime);
            } else {
                endTime.removeAttribute('min');
            }
        });

        // Ensure End Date is not before Start Date
        endDate.addEventListener('input', function () {
            if (endDate.value === startDate.value) {
                endTime.setAttribute('min', startTime.value || currentTime);
            } else {
                endTime.removeAttribute('min');
            }
        });

        // Set initial restrictions for Start Time and End Time on page load
        if (startDate.value === today) {
            startTime.setAttribute('min', currentTime);
        }
    });
</script>

</body>
</html>
