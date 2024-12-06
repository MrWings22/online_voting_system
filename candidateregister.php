<?php
session_start();
include 'db_connection.php'; // Ensure you have your database connection file
require_once 'header_back.php';
// Render the header with the appropriate back link
renderHeader('index.php');
// Initialize variables
$position = '';
$gpa = '';
$backpapers_count = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data safely
    $username = $_POST['username'];
    $admission_no = $_POST['admission_no'];
    $position = $_POST['position']; // Safely access after form submission
    $fullname = $_POST['fullname'];
    $gender = $_POST['gender'];
    $year = $_POST['year'];
    $batch = $_POST['batch'];
    $department = $_POST['department'];
    $backpapers = isset($_POST['backpapers']) ? $_POST['backpapers'] : 'No';
    $no_backpapers = isset($_POST['no_backpapers']) ? $_POST['no_backpapers'] : 0; // Get backpapers count if provided
    $description = $_POST['description'];
    $signature = isset($_POST['signature']) ? 'Yes' : 'No';

// Get GPA input directly since it's now required for every candidate
$gpa = $_POST['gpa'];

   
    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check !== false) {
       
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["photo"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["photo"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
   // After collecting the form data and before inserting into the candidate table

// Check if the username exists in the users table
$check_user_query = "SELECT * FROM users WHERE username='$username'";
$check_user_result = mysqli_query($conn, $check_user_query);

if (mysqli_num_rows($check_user_result) == 0) {
    echo "<script>
    alert('Username does not exist. Please register the user first.');
    window.location.href = 'userregistration.php';
    </script>";
    exit(); // Stop further execution
}

// Proceed to insert the candidate's data into the database
$query = "INSERT INTO candidate (username, admission_no, position, fullname, gender, year, batch, department, backpapers, no_backpapers, gpa, description, photo, signature) 
          VALUES ('$username','$admission_no', '$position', '$fullname', '$gender', '$year', '$batch', '$department', '$backpapers', '$no_backpapers', '$gpa','$description', '$target_file', '$signature')";

if (mysqli_query($conn, $query)) {
    echo "<script>
    alert('Registration successful!');
    window.location.href = 'login.php';
    </script>";
} else {
    echo "Error: " . mysqli_error($conn);
}


    // Close database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Registration</title>
    <link rel="stylesheet" href="candidateregister.css">
    <script>
        function toggleBackpapersInput() {
            var backpapersSelect = document.getElementById('backpapers');
            var backpapersCountInput = document.getElementById('backpapers_count_container');
            if (backpapersSelect.value === 'Yes') {
                backpapersCountInput.style.display = 'block';
            } else {
                backpapersCountInput.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div class="registration-container">
        <h2>Candidate Registration Form</h2>
        <form action="" method="POST" enctype="multipart/form-data">
        <label for="username">Student username:</label>
        <input type="text" id="username" name="username" required>

            <label for="admission_no">Admission No:</label>
            <input type="text" id="admission_no" name="admission_no" required>

            <label for="position">Position:</label>
            <select id="position" name="position" required>
                <option value="">Select Position</option>
                <option value="Main Union" <?php echo ($position == 'Main Union') ? 'selected' : ''; ?>>Main Union</option>
                <option value="Department Representative" <?php echo ($position == 'Department Representative') ? 'selected' : ''; ?>>Department Representative</option>
                <option value="Class Representative" <?php echo ($position == 'Class Representative') ? 'selected' : ''; ?>>Class Representative</option>
            </select>

            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label for="year">Year of Study:</label>
            <select id="year" name="year" required>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
            </select>

            <label for="batch">Batch:</label>
            <input type="text" id="batch" name="batch" placeholder="Optional">

            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <option value="">Select Department</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Media ">Media</option>
                <option value="Commerce">Commerce</option>
                <option value="Social Work">Social Work</option>
                <!-- Add more departments as needed -->
            </select>

            <label for="backpapers">Backpapers:</label>
            <select id="backpapers" name="backpapers" onchange="toggleBackpapersInput()">
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>

            <div id="backpapers_count_container" style="display: none;">
                <label for="no_backpapers">How many backpapers:</label>
                <input type="number" id="no_backpapers" name="no_backpapers" min="0" placeholder="Enter number of backpapers">
            </div>

            <label for="gpa">GPA:</label>
            <input type="number" step="0.01" id="gpa" name="gpa" min="0" max="10.00" placeholder="Enter your GPA" required>


            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>

            <label for="photo">Upload Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>

            <label for="signature">Digital Signature:</label>
            <div id="signature-container">
            <input type="checkbox" id="signature" name="signature" required>
            <label for="signature">I confirm that the information provided is accurate and complete.</label>
            </div>

            <input type="submit" value="Register">
        </form>
    </div>

    <?php include 'footerall.php'; ?>
</body>
</html>
