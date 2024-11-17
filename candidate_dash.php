<?php
session_start();
include 'db_connection.php';
require_once 'header_back.php';

// Render the header with the appropriate back link
renderHeader('index.php');

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

// Fetch available elections
$elections_query = "SELECT * FROM elections";
$elections_result = mysqli_query($conn, $elections_query);

// If the form is submitted to update details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $fullname = $_POST['fullname'];
    $year = $_POST['year'];
    $batch = $_POST['batch'];
    $department = $_POST['department'];
    $election_id = $_POST['election']; // Get the selected election ID
    $position = $_POST['position'];
    $description = $_POST['description'];

    // Photo upload handling
    $photo = $_FILES['photo']['name'];
    $target_dir = "uploads/"; // Specify your uploads directory
    $target_file = $target_dir . basename($photo);
    $uploadOk = 1;

    // Check if a file is uploaded
    if (!empty($photo)) {
        // Check if the file is a valid image
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if ($check === false) {
            echo "<script>alert('File is not an image.');</script>";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "<script>alert('Sorry, file already exists.');</script>";
            $uploadOk = 0;
        }

        // Check file size (optional)
        if ($_FILES['photo']['size'] > 500000) {
            echo "<script>alert('Sorry, your file is too large.');</script>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            $uploadOk = 0;
        }

        // If everything is ok, try to upload the file
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                // Update candidate details and election participation with the new photo
                $update_query = "UPDATE candidate SET 
                    fullname = '$fullname', 
                    year = '$year', 
                    batch = '$batch', 
                    department = '$department', 
                    election = '$election_id',
                    position = '$position', 
                    photo = '$target_file', 
                    description = '$description'
                WHERE candidate_id = $candidate_id";
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        }
    } else {
        // If no new photo is uploaded, just update other details without changing the photo
        $update_query = "UPDATE candidate SET 
            fullname = '$fullname', 
            year = '$year', 
            batch = '$batch', 
            department = '$department', 
            election = '$election_id',
            position = '$position', 
            description = '$description'
        WHERE candidate_id = $candidate_id";
    }

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Details updated successfully!');</script>";
        // Refresh candidate data after update
        $candidate = mysqli_fetch_assoc(mysqli_query($conn, $query));
    } else {
        echo "Error updating details: " . mysqli_error($conn);
    }
}

// Check if the user clicked the 'Edit' button
$is_editing = isset($_POST['edit']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $candidate['fullname']; ?></h2>

    <!-- Candidate details -->
    <h3>Your Details</h3>
    <form action="candidate_dashedit.php" method="POST" enctype="multipart/form-data">
        <label>Full Name:</label>
        <?php if ($is_editing): ?>
            <input type="text" name="fullname" value="<?php echo $candidate['fullname']; ?>" required><br>
        <?php else: ?>
            <span><?php echo $candidate['fullname']; ?></span><br>
        <?php endif; ?>

        <label>Year of Study:</label>
        <?php if ($is_editing): ?>
            <input type="text" name="year" value="<?php echo $candidate['year']; ?>" required><br>
        <?php else: ?>
            <span><?php echo $candidate['year']; ?></span><br>
        <?php endif; ?>

        <label>Batch:</label>
        <?php if ($is_editing): ?>
            <input type="text" name="batch" value="<?php echo $candidate['batch']; ?>" required><br>
        <?php else: ?>
            <span><?php echo $candidate['batch']; ?></span><br>
        <?php endif; ?>

        <label>Department:</label>
        <?php if ($is_editing): ?>
            <input type="text" name="department" value="<?php echo $candidate['department']; ?>" required><br>
        <?php else: ?>
            <span><?php echo $candidate['department']; ?></span><br>
        <?php endif; ?>

        <!-- Select election -->
        <label>Select Election:</label>
        <?php if ($is_editing): ?>
            <select name="election" required>
                <?php while ($row = mysqli_fetch_assoc($elections_result)) { ?>
                    <option value="<?php echo $row['election_id']; ?>" 
                        <?php if ($candidate['election'] == $row['election_id']) echo 'selected'; ?>>
                        <?php echo $row['title']; ?>
                    </option>
                <?php } ?>
            </select><br>
        <?php else: ?>
            <span><?php echo $candidate['election_title']; ?></span><br> <!-- Show the election title -->
        <?php endif; ?>

        <label>Position:</label>
        <?php if ($is_editing): ?>
            <input type="text" name="position" value="<?php echo $candidate['position']; ?>" required><br>
        <?php else: ?>
            <span><?php echo $candidate['position']; ?></span><br>
        <?php endif; ?>

        <label>Description:</label>
        <?php if ($is_editing): ?>
            <textarea name="description" required><?php echo $candidate['description']; ?></textarea><br>
        <?php else: ?>
            <span><?php echo $candidate['description']; ?></span><br>
        <?php endif; ?>

        <!-- Show photo upload option only when editing -->
        <?php if ($is_editing): ?>
            <label>Photo:</label>
            <input type="file" name="photo"><br>
            <!-- Display current photo if available -->
            <?php if (!empty($candidate['photo'])): ?>
                <img src="<?php echo $candidate['photo']; ?>" alt="Candidate Photo" style="max-width: 200px; max-height: 200px;"><br>
            <?php endif; ?>
        <?php else: ?>
            <!-- Display current photo if available without upload option -->
            <?php if (!empty($candidate['photo'])): ?>
                <img src="<?php echo $candidate['photo']; ?>" alt="Candidate Photo" style="max-width: 200px; max-height: 200px;"><br>
            <?php endif; ?>
        <?php endif; ?>

        <!-- If editing, show the update button -->
        <?php if ($is_editing): ?>
            <input type="hidden" name="update" value="true">
            <input type="submit" value="Update Details">
        <?php else: ?>
            <input type="hidden" name="edit" value="true">
            <input type="submit" value="Edit">
        <?php endif; ?>
    </form>

    <a href="logout.php">Logout</a>

    <?php include 'footerall.php'; ?>
</body>
</html>
<?php
// Close the database connection
mysqli_close($conn);
?>
