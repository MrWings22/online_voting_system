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

// Fetch the list of candidates from the database, including votes and positions
$candidates_query = "SELECT * FROM candidate"; // Assuming the 'candidate' table has a 'position' column
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
    </style>
</head>
<body>
    <h2>Candidate List</h2>
    <table>
        <thead>
            <tr>
                <th>Candidate Name</th>
                <th>Department</th>
                <th>Total Votes</th> <!-- Column for votes -->
                <th>Position</th> <!-- Changed column for position -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($candidate = mysqli_fetch_assoc($candidates_result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($candidate['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($candidate['department']); ?></td>
                    <td><?php echo htmlspecialchars($candidate['votes']); ?></td> <!-- Display total votes -->
                    <td><?php echo htmlspecialchars($candidate['position']); ?></td> <!-- Display position -->
                    <td>
                        <form action="admin_candida.php" method="POST" style="display:inline;">
                            <input type="hidden" name="candidate_id" value="<?php echo $candidate['candidate_id']; ?>">
                            <?php if ($candidate['approved']) { ?>
                                <input type="submit" name="disapprove" value="Disapprove" onclick="return confirm('Are you sure you want to disapprove this candidate?');">
                            <?php } else { ?>
                                <input type="submit" name="approve" value="Approve" onclick="return confirm('Are you sure you want to approve this candidate?');">
                            <?php } ?>
                            <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this candidate?');">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php include 'footerall.php'; ?>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
