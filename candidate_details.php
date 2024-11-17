<?php
session_start();
include 'db_connection.php';
require_once 'header_back.php';
// Render the header with the appropriate back link
renderHeader('admin.php');
// Ensure only admin can access this page
if (!isset($_SESSION['ad_username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch the candidate details
if (isset($_GET['candidate_id'])) {
    $candidate_id = $_GET['candidate_id'];
    $query = "SELECT * FROM candidate WHERE candidate_id = $candidate_id";
    $result = mysqli_query($conn, $query);
    $candidate = mysqli_fetch_assoc($result);
} else {
    echo "Candidate ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Details</title>
</head>
<body>
    <h2>Candidate Details</h2>
    <?php if ($candidate) { ?>
        <p><strong>Full Name:</strong> <?php echo $candidate['fullname']; ?></p>
        <p><strong>Position:</strong> <?php echo $candidate['position']; ?></p>
        <p><strong>Gender:</strong> <?php echo $candidate['gender']; ?></p>
        <p><strong>Year:</strong> <?php echo $candidate['year']; ?></p>
        <p><strong>Batch:</strong> <?php echo $candidate['batch']; ?></p>
        <p><strong>Department:</strong> <?php echo $candidate['department']; ?></p>
        <p><strong>Backpapers:</strong> <?php echo $candidate['backpapers']; ?></p>
        <p><strong>Number of Backpapers:</strong> <?php echo $candidate['no_backpapers']; ?></p>
        <p><strong>GPA:</strong> <?php echo $candidate['gpa']; ?></p>
        <p><strong>Description:</strong> <?php echo $candidate['description']; ?></p>
        <p><strong>Signature:</strong> <?php echo $candidate['signature'] == 'Yes' ? 'Confirmed' : 'Not Confirmed'; ?></p>
        <p><strong>Candidate Photo:</strong></p><br>
        <img src="<?php echo $candidate['photo']; ?>" alt="Candidate Photo" style="max-width: 200px;">
    <?php } else { ?>
        <p>No candidate found.</p>
    <?php } ?>

    <?php include 'footerall.php'; ?>
</body>
</html>
