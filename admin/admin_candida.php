<?php
session_start();
include 'db_connection.php';
require_once 'header_back.php';
renderHeader('admin.php');

// Check if admin is logged in
if (!isset($_SESSION['ad_username'])) {
    header("Location: admin_login.php");
    exit();
}

// Get the filter parameter from the URL (if any)
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

// Fetch the list of candidates based on the filter
if ($filter == 'All') {
    $candidates_query = "SELECT * FROM candidate ORDER BY position";
} else {
    $candidates_query = "SELECT * FROM candidate WHERE position = '$filter' ORDER BY position";
}

$candidates_result = mysqli_query($conn, $candidates_query);

// Check if a candidate is to be deleted or approved/disapproved
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $candidate_id = $_POST['candidate_id'];
        $delete_query = "DELETE FROM candidate WHERE candidate_id = $candidate_id";

        if (mysqli_query($conn, $delete_query)) {
            echo "<script>alert('Candidate deleted successfully!');</script>";
            header("Refresh:0"); // Refresh the page to update the list
            exit();
        } else {
            echo "Error deleting candidate: " . mysqli_error($conn);
        }
    }

    if (isset($_POST['approve'])) {
        $candidate_id = $_POST['candidate_id'];
        $approve_query = "UPDATE candidate SET approved = 1 WHERE candidate_id = $candidate_id";

        if (mysqli_query($conn, $approve_query)) {
            echo "<script>alert('Candidate approved successfully!');</script>";
            header("Refresh:0"); // Refresh the page to update the list
            exit();
        } else {
            echo "Error approving candidate: " . mysqli_error($conn);
        }
    }

    if (isset($_POST['disapprove'])) {
        $candidate_id = $_POST['candidate_id'];
        $disapprove_query = "UPDATE candidate SET approved = 0 WHERE candidate_id = $candidate_id";

        if (mysqli_query($conn, $disapprove_query)) {
            echo "<script>alert('Candidate disapproved successfully!');</script>";
            header("Refresh:0"); // Refresh the page to update the list
            exit();
        } else {
            echo "Error disapproving candidate: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Candidate List</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item <?php echo $filter == 'All' ? 'active' : ''; ?>">
                    <a class="nav-link" href="admin_candida.php?filter=All">All Candidates</a>
                </li>
                <li class="nav-item <?php echo $filter == 'Class Representative' ? 'active' : ''; ?>">
                    <a class="nav-link" href="admin_candida.php?filter=Class Representative">Class Representative</a>
                </li>
                <li class="nav-item <?php echo $filter == 'Department Representative' ? 'active' : ''; ?>">
                    <a class="nav-link" href="admin_candida.php?filter=Department Representative">Department Representative</a>
                </li>
                <li class="nav-item <?php echo $filter == 'Main Union' ? 'active' : ''; ?>">
                    <a class="nav-link" href="admin_candida.php?filter=Main Union">Main Union</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Back</a>
                </li>
            </ul>
        </div>
    </nav>

    <h2>Candidate List (<?php echo $filter; ?>)</h2>

    <!-- Candidate List Table -->
    <table>
        <thead>
            <tr>
                <th>Candidate Name</th>
                <th>Department</th>
                <th>Total Votes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($candidate = mysqli_fetch_assoc($candidates_result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($candidate['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($candidate['department']); ?></td>
                    <td><?php echo htmlspecialchars($candidate['votes']); ?></td>
                    <td>
                        <form action="admin_candida.php" method="POST" style="display:inline;">
                        <input type="hidden" name="candidate_id" value="<?php echo $candidate['candidate_id']; ?>">
        
                        <!-- Approve/Disapprove buttons -->
                        <?php if ($candidate['approved']) { ?>
                        <input type="submit" name="disapprove" value="Disapprove" onclick="return confirm('Are you sure you want to disapprove this candidate?');">
                        <?php } else { ?>
                        <input type="submit" name="approve" value="Approve" onclick="return confirm('Are you sure you want to approve this candidate?');">
                        <?php } ?>
        
                        <!-- Delete button -->
                        <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this candidate?');">

    
                        <!-- View Details link -->
                        <a href="candidate_details.php?candidate_id=<?php echo $candidate['candidate_id']; ?>" class="btn btn-info btn-sm" style="margin-left: 10px;">View Details</a>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
