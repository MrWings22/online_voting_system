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

// Fetch candidate details
$query = "SELECT * FROM candidate WHERE candidate_id = $candidate_id";
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
    $election_id = $_POST['election'];
    $position = $_POST['position'];
    $description = $_POST['description'];

    // Photo upload handling
    $photo = $_FILES['photo']['name'];
    $target_dir = "uploads/"; // Specify your uploads directory
    $target_file = $target_dir . basename($photo);
    $uploadOk = 1;

    // Check if a new file is uploaded
    if (!empty($photo)) {
        // Check if the file is a valid image
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if ($check === false) {
            echo "<script>alert('File is not an image.');</script>";
            $uploadOk = 0;
        }

        // Check file size
        $fileSize = $_FILES['photo']['size']; // Get the file size
        if ($fileSize > 500000) { // 500KB limit
            echo "<script>alert('Sorry, your file is too large.');</script>";
            $uploadOk = 0;
        } else {
            echo "<script>alert('File size is acceptable: " . $fileSize . " bytes.');</script>"; // Debugging
        }

        // Allow certain file formats
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            $uploadOk = 0;
        }

        // If everything is ok, try to upload the file
        if ($uploadOk == 1) {
            // Delete the old photo if it exists
            if (!empty($candidate['photo']) && file_exists($candidate['photo'])) {
                unlink($candidate['photo']); // Delete the old photo file
            }

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

                if (mysqli_query($conn, $update_query)) {
                    echo "<script>alert('Details updated successfully!');</script>";
                    header("Location: candidate_dash.php"); // Redirect after update
                    exit();
                } else {
                    echo "Error updating details: " . mysqli_error($conn);
                }
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

        if (mysqli_query($conn, $update_query)) {
            echo "<script>alert('Details updated successfully!');</script>";
            header("Location: candidate_dash.php"); // Redirect after update
            exit();
        } else {
            echo "Error updating details: " . mysqli_error($conn);
        }
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
    <link rel="stylesheet" href="candidate_dashedit.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($candidate['fullname']); ?></h2>
    
    <!-- Candidate details -->
    <h3>Your Details</h3>
    <form action="candidate_dashedit.php" method="POST" enctype="multipart/form-data">
    <label><i class="fas fa-user"></i>Full Name:</label>
    <input type="text" name="fullname" value="<?php echo htmlspecialchars($candidate['fullname']); ?>" <?php echo !$is_editing ? 'readonly' : ''; ?> required><br>

    <label><i class="fas fa-graduation-cap"></i>Year of Study:</label>
    <input type="text" name="year" value="<?php echo htmlspecialchars($candidate['year']); ?>" <?php echo !$is_editing ? 'readonly' : ''; ?> required><br>

    <label><i class="fas fa-users"></i>Batch:</label>
    <input type="text" name="batch" value="<?php echo htmlspecialchars($candidate['batch']); ?>" <?php echo !$is_editing ? 'readonly' : ''; ?> required><br>

    <label><i class="fas fa-building"></i>Department:</label>
    <input type="text" name="department" value="<?php echo htmlspecialchars($candidate['department']); ?>" <?php echo !$is_editing ? 'readonly' : ''; ?> required><br>

    <label><i class="fas fa-flag"></i>Position:</label>
    <input type="text" name="position" value="<?php echo htmlspecialchars($candidate['position']); ?>" <?php echo !$is_editing ? 'readonly' : ''; ?> required><br>

        <!-- Select election -->
        <label>Select Election:</label>
        <select name="election" <?php echo !$is_editing ? 'disabled' : ''; ?> required>
            <?php while ($row = mysqli_fetch_assoc($elections_result)) { ?>
                <option value="<?php echo $row['election_id']; ?>" 
                    <?php if ($candidate['election'] == $row['election_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($row['title']); ?>
                </option>
            <?php } ?>
        </select>
        <br>

        <!-- Photo -->
        <label>Photo:</label><br>
        <img src="<?php echo htmlspecialchars($candidate['photo']); ?>" alt="Candidate Photo" style="max-width: 150px; max-height: 150px;"><br>
        <?php if ($is_editing): ?>
            <input type="file" name="photo"><br>
        <?php endif; ?>

        <!-- Description -->
        <label>Description:</label>
        <textarea name="description" <?php echo !$is_editing ? 'readonly' : ''; ?>><?php echo htmlspecialchars($candidate['description']); ?></textarea><br>

        <!-- If editing, show the update button -->
        <?php if ($is_editing): ?>
            <input type="hidden" name="update" value="true">
            <input type="submit" value="Update Details">
        <?php else: ?>
            <input type="hidden" name="edit" value="true">
            <input type="submit" value="Edit">
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </form>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
