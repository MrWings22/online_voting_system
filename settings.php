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

.voter-id-card {
    width: 300px;
    height: 450px;
    border: 2px solid #3B2A77; /* Purple border */
    border-radius: 15px;
    background-color: white;
    font-family: 'Arial', sans-serif;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    margin: 20px auto; /* Center the card */
}

.voter-id-card-header {
    background-color: #3B2A77; /* Purple header */
    color: white;
    text-align: center;
    padding: 15px;
}

.voter-id-card-header h2 {
    margin: 0;
    font-size: 17px;
}

.voter-id-card-header p {
    margin: 5px 0 0;
    font-size: 12px;
}

.voter-id-card-body {
    height: 50%;
    padding: 10px;
    text-align: center;
}

.user-photo {
    width: 80px;
    height: 80px;
    background-color: #ddd;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 36px;
    color: #555;
    font-weight: bold;
}

.user-info {
    text-align: left;
    font-size: 11px;
    color: #333;
    margin: 10px 0;
}

.user-info h3 {
    text-align: center;
    margin-bottom: 10px;
    font-size: 16px;
}


.voter-id-card-footer {
    background-color: #f4f4f4;
    padding: 10px;
    text-align: center;
    font-size: 12px;
    color: #555;
}

/* Footer Section */
.id-card-footer {
    text-align: center;
    height: 5px;
    font-size: 12px;
    color: #666;
    position: absolute;
    bottom: 2px;
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
    <div class="voter-id-card">
        <!-- Header -->
        <div class="voter-id-card-header">
            <h2>Bishop Vayalil Memorial Holy Cross College</h2>
            <p>Cherpunkal, Pala, Kottayam, 686584</p>
        </div>
        <h3>VOTER ID CARD</h3>
        <!-- Photo and Details -->
        <div class="voter-id-card-body">
            <!-- User Photo Placeholder -->
            <div class="user-photo">
                <?php echo strtoupper(substr($user['fname'], 0, 1) . substr($user['lname'], 0, 1)); ?>
            </div>

            <!-- User Info -->
            <div class="user-info">
                <h3><?php echo $user['fname'] . ' ' . $user['lname']; ?></h3>
                <p><strong>Voter ID:</strong> <?php echo $user['user_id']; ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $user['date_of_birth']; ?></p>
                <p><strong>Department:</strong> <?php echo $user['department']; ?></p>
                <p><strong>Batch:</strong> <?php echo $user['batch']; ?></p>
                <p><strong>Year:</strong> <?php echo $user['year']; ?></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="voter-id-card-footer">
            <p>VOTER ID Card for Students</p>
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
