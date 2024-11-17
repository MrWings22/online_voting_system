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
    // Check if the username already exists in the database
    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Username already exists
        echo "<script>
                alert('Username is already taken. Please choose another one.');
                window.location.href = 'userregistration.php';
              </script>";
    } else {
        // Insert the new user if the username is not taken
        $query = "INSERT INTO users (username, password, fname, lname, department, year, date_of_birth, gender, batch)
                  VALUES ('$username', '$password', '$fname', '$lname', '$department', '$year', '$date_of_birth', '$gender', '$batch')";

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
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Voting System</title>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form method="POST" action="userregistration.php">
            <label>Username</label>
            <input type="text" name="username" required><br>
            <label>Password</label>
            <input type="password" name="password" required><br>
            <label>First Name</label>
            <input type="text" name="fname" required><br>
            <label>Last Name</label>
            <input type="text" name="lname" required><br>
            <label for="department">Department:</label>
            <select name="department" id="department" required>
                <option value="">--Select Department--</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Social Work">Social Work</option>
                <option value="Commerce">Commerce</option>
                <option value="Media">Media</option>
            </select><br>
            <label for="batch">Batch:</label>
            <select name="batch" id="batch">
                <option value="">--Batch--</option>
                <option value="A">A</option>
                <option value="B">B</option>
            </select><br>
            <label>Year</label>
            <input type="text" name="year" required><br>
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" required><br>
            <label>Gender</label>
            <select name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select><br>
            <input type="submit" value="Register">
        </form>
    </div>

    <?php include 'footerall.php'; ?>
</body>
</html>
