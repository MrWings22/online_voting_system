<?php
// Start the session to keep track of the logged-in user
session_start();

// Check if the user is logged in by verifying the session
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit;  // Stop further execution of the script
}

// Get the logged-in username from the session
$username = $_SESSION['username']; 

// Include the database connection file to interact with the database
include 'db_connection.php';


// Query the database to fetch user details based on the logged-in username
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);

// Fetch user data as an associative array
$user = mysqli_fetch_assoc($result);

// Check if user data was found
if (!$user) {
    echo "User not found.";  // If user doesn't exist, show an error
    exit;  // Stop further execution
}

// Handle profile update when the form is submitted
if (isset($_POST['edit_profile'])) {
    // Get the new profile details from the form
    $new_fname = $_POST['fname'];
    $new_lname = $_POST['lname'];
    $new_department = $_POST['department'];
    $new_batch = $_POST['batch'];
    $new_date_of_birth = $_POST['date_of_birth'];
    $new_gender = $_POST['gender'];

    // Update user data in the database with the new details
    $update_query = "UPDATE users SET fname = '$new_fname', lname = '$new_lname', department = '$new_department', batch = '$new_batch', date_of_birth = '$new_date_of_birth', gender = '$new_gender' WHERE username = '$username'";
    mysqli_query($conn, $update_query);

    // Redirect to the settings page to reflect the changes
    header("Location: settings.php");
    exit();
}

// Handle password change when the form is submitted
// Handle password change when the form is submitted
if (isset($_POST['change_password'])) {
    // Get the current and new passwords from the form
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify if the current password matches the one in the database
    if ($current_password === $user['password']) {  // Compare plain text password
        // Check if the new password matches the confirmation
        if ($new_password === $confirm_password) {
            // Update the password in the database (plain text)
            $update_password_query = "UPDATE users SET password = '$new_password' WHERE username = '$username'";
            mysqli_query($conn, $update_password_query);

            // Show a success message
            echo "Password changed successfully!";
        } else {
            echo "New passwords do not match.";  // Show an error if passwords don't match
        }
    } else {
        echo "Incorrect current password.";  // Show an error if the current password is wrong
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <style>
        /* Basic styling for the page */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            display: flex;
            justify-content: space-between;
        }

        .container {
            width: 48%;
        }

        .profile-card {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            background-color: white;
            text-align: center;
        }

        .input-field {
            padding: 10px;
            margin: 5px;
            width: 200px;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            margin: 10px 0;
        }

        .form-container {
            margin: 10px 0;
        }

        /* Styling for the profile picture */
        .profile-img {
            width: 100px;
            height: 100px;
            background-color: #ddd;
            color: white;
            font-size: 40px;
            text-align: center;
            line-height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

/* Voter ID Card Styling */
.id-card {
    width: 350px;
    height: 220px;
    border: 1px solid #ccc;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    background-color: #fdfdfd;
    padding: 10px;
    font-family: Arial, sans-serif;
    position: relative;
}

/* Header Section */
.id-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.id-card-header h2 {
    font-size: 16px;
    text-align: center;
    color: #333;
    flex-grow: 1;
}

/* Body Section */
.id-card-body {
    display: flex;
}

.details {
    line-height: 1.6;
    font-size: 14px;
    color: #333;
}

.details p {
    margin: 5px 0;
}

/* Footer Section */
.id-card-footer {
    text-align: center;
    font-size: 12px;
    color: #666;
    position: absolute;
    bottom: 10px;
    left: 0;
    width: 100%;
    font-style: italic;
}

        .toggle-button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Left Container (Profile Section) -->
    <div class="container">
        <div class="profile-card">
            <!-- Profile Picture -->
            <div class="profile-img">
                <?php echo strtoupper(substr($user['fname'], 0, 1) . substr($user['lname'], 0, 1)); ?>
            </div>
            <h2><?php echo $user['fname'] . ' ' . $user['lname']; ?></h2>
            <p>User ID: <?php echo $user['user_id']; ?></p>
            <p>Department: <?php echo $user['department']; ?></p>
            <p>Batch: <?php echo $user['batch'] .'<br>Year: '. $user['year']; ?></p>
            <p>Date of Birth: <?php echo $user['date_of_birth']; ?></p>
            <p>Gender: <?php echo $user['gender']; ?></p>

            <!-- Button to show the edit profile form -->
            <form method="POST" action="settings.php">
                <div class="form-container">
                    <button class="button" type="button" onclick="showEditForm()">Edit Profile</button>
                </div>
                <!-- Form for editing profile, initially hidden -->
                <div id="editForm" style="display:none;">
                    <div class="form-container">
                        <label for="fname">First Name:</label>
                        <input class="input-field" type="text" name="fname" value="<?php echo $user['fname']; ?>" />
                    </div>
                    <div class="form-container">
                        <label for="lname">Last Name:</label>
                        <input class="input-field" type="text" name="lname" value="<?php echo $user['lname']; ?>" />
                    </div>
                    <div class="form-container">
                        <label for="department">Department:</label>
                        <input class="input-field" type="text" name="department" value="<?php echo $user['department']; ?>" />
                    </div>
                    <div class="form-container">
                        <label for="batch">Batch:</label>
                        <input class="input-field" type="text" name="batch" value="<?php echo $user['batch']; ?>" />
                    </div>
                    <div class="form-container">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input class="input-field" type="date" name="date_of_birth" value="<?php echo $user['date_of_birth']; ?>" />
                    </div>
                    <div class="form-container">
                        <label for="gender">Gender:</label>
                        <input class="input-field" type="text" name="gender" value="<?php echo $user['gender']; ?>" />
                    </div>
                    <!-- Save button to submit profile changes -->
                    <button class="button" type="submit" name="edit_profile">Save Profile</button>
                </div>
            </form>
        </div>
        <a href="voterpage.php" > Back to Previous Page</a>
    </div>

    <!-- Right Container (Password Change & ID Card Section) -->
    <div class="container">
        <div class="profile-card">
            <h3>Change Password</h3>
            <button class="button" onclick="showPasswordForm()">Show Password Change Form</button>

            <!-- Password change form, initially hidden -->
            <div id="passwordForm" style="display:none;">
                <form method="POST" action="settings.php">
                    <div class="form-container">
                        <label for="current_password">Current Password:</label>
                        <input class="input-field" type="password" name="current_password" required />
                    </div>
                    <div class="form-container">
                        <label for="new_password">New Password:</label>
                        <input class="input-field" type="password" name="new_password" required />
                    </div>
                    <div class="form-container">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input class="input-field" type="password" name="confirm_password" required />
                    </div>
                    <button class="button" type="submit" name="change_password">Change Password</button>
                </form>
            </div>
        </div>

<!-- Voter ID Card Section -->
<div class="profile-card" id="idCardSection" style="display:none;">
    <div class="id-card">
        <!-- Election Commission Header -->
        <div class="id-card-header">
            <div class="logo"></div>
            <h2>VOTER ID CARD</h2>
            <div class="eci-icon"></div>
        </div>

        <!-- Photo Placeholder and User Details -->
        <div class="id-card-body">

            <!-- Details Section -->
            <div class="details">
                <p><strong>Full Name:</strong> <?php echo $user['fname'] . ' ' . $user['lname']; ?></p>
                <p><strong>Voter ID:</strong> <?php echo $user['user_id']; ?></p>
                <p><strong>Department:</strong> <?php echo $user['department']; ?></p>
                <p><strong>Batch:</strong> <?php echo $user['batch']; ?></p>
                <p><strong>Year:</strong> <?php echo date('Y', strtotime($user['date_of_birth'])); ?></p>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="id-card-footer">
            <p>VOTER ID CARD</p>
        </div>
    </div>
</div>
        <button class="toggle-button" onclick="toggleIdCard()">Show/Hide Voter ID Card</button>
    </div>
    

    <script>
        // Show the edit profile form
        function showEditForm() {
            var form = document.getElementById("editForm");
            form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
        }

        // Show the password change form
        function showPasswordForm() {
            var form = document.getElementById("passwordForm");
            form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
        }

        // Toggle the Voter ID Card visibility
        function toggleIdCard() {
            var idCardSection = document.getElementById("idCardSection");
            idCardSection.style.display = (idCardSection.style.display === "none" || idCardSection.style.display === "") ? "block" : "none";
        }
    </script>
    
</body>
</html>
