<?php
include 'db_connection.php';
require_once 'header_back.php';
// Render the header with the appropriate back link
renderHeader('index.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $batch = $_POST['batch'];

    $query = "INSERT INTO users (username, password, fname, lname, department, year, date_of_birth, gender,batch)
              VALUES ('$username', '$password', '$fname', '$lname', '$department', '$year', '$date_of_birth', '$gender','$batch')";
    

        if (mysqli_query($conn, $query)) {
        // Show a JavaScript alert and redirect
        echo "<script>
                alert('Registration successful!');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Voting System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="userregistration.css">
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form method="POST" action="userregistration.php">
            <div class="input-group">
                <label><i class="fas fa-user"></i> Username</label>
                <input type="text" name="username" required><br>
            </div>

            <div class="input-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" required><br>
            </div>

            <div class="input-group">
                <label><i class="fas fa-id-card"></i> First Name</label>
                <input type="text" name="fname" required><br>
            </div>

            <div class="input-group">
                <label><i class="fas fa-id-card-alt"></i> Last Name</label>
                <input type="text" name="lname" required><br>
            </div>

            <div class="input-group">
                <label><i class="fas fa-building"></i> Department</label>
                <select name="department" id="department" required>
                    <option value="">--Select Department--</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Social Work">Social Work</option>
                    <option value="Commerce">Commerce</option>
                    <option value="Media">Media</option>
                </select><br>
            </div>

            <div class="input-group">
                <label><i class="fas fa-users"></i> Batch</label>
                <select name="batch" id="batch">
                    <option value="">--Batch--</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                </select><br>
            </div>

            <div class="input-group">
                <label><i class="fas fa-calendar-alt"></i> Year</label>
                <select name="year" id="year">
                    <option value="">--Year--</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select><br>
            </div>

            <div class="input-group">
                <label><i class="fas fa-birthday-cake"></i> Date of Birth</label>
                <input type="date" name="date_of_birth" required><br>
            </div>

            <div class="input-group">
                <label><i class="fas fa-venus-mars"></i> Gender</label>
                <select name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select><br>
            </div>

            <input type="submit" value="Register">
        </form>
    </div>

</body>
</html>
