<?php
session_start();
include 'db_connection.php';
require_once 'header_back.php';
renderHeader('index.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Fetch the user details
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Check if the user has already voted
if ($user['voted'] == 1) {
    echo "<script>
                alert('ഇനി നോക്കണ്ട ഉണ്ണിയെ നീ ഒന്ന് വോട്ട് ചെയിതെ അല്ലേടാ!');
                window.location.href = 'voterpage.php';
              </script>";
    // Redirect to an already voted page
    exit;
}

// Get the selected election, department, batch, and year from the previous form submission
$department = isset($_POST['department']) ? $_POST['department'] : $_SESSION['department'] ?? null;
$year = isset($_POST['year']) ? $_POST['year'] : $_SESSION['year'] ?? null;
$batch = isset($_POST['batch']) ? $_POST['batch'] : $_SESSION['batch'] ?? null;

// Store selected department, year, and batch in session to use later
if ($department && $year && $batch) {
    $_SESSION['department'] = $department;
    $_SESSION['year'] = $year;
    $_SESSION['batch'] = $batch;
}

// Ensure that election_id is available in session
if (isset($_POST['election_id'])) {
    $_SESSION['election_id'] = $_POST['election_id'];
}

$election_id = $_SESSION['election_id'] ?? null; // Default to null if not set

// Fetch only approved candidates for the Main Union
$queryUnionCandidates = "SELECT * FROM candidate WHERE position = 'Main Union' AND approved = 1";
$resultUnionCandidates = mysqli_query($conn, $queryUnionCandidates);

// Fetch only approved candidates for the Department Representative based on the selected department
$queryDeptRepCandidates = "SELECT * FROM candidate WHERE department = '$department' AND position = 'Department Representative' AND approved = 1";
$resultDeptRepCandidates = mysqli_query($conn, $queryDeptRepCandidates);

// Fetch only approved candidates for Class Representative based on the selected department, batch, and year
$queryClassRepCandidates = "SELECT * FROM candidate WHERE department = '$department' AND batch = '$batch' AND year = '$year' AND position = 'Class Representative' AND approved = 1";
$resultClassRepCandidates = mysqli_query($conn, $queryClassRepCandidates);

// Check if form is submitted to handle voting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_votes'])) {
    $unionVote = $_POST['union_vote'] ?? null;
    $deptRepVote = $_POST['dept_rep_vote'] ?? null;
    $classRepVote = $_POST['class_rep_vote'] ?? null;

    // Initialize an error message variable
    $error = '';

    // Check if all categories have votes
    if ($unionVote && $deptRepVote && $classRepVote) {
        // Update vote counts in the database
        $success = true; // Variable to track if all updates were successful

        if ($unionVote) {
            $query = "UPDATE candidate SET votes = votes + 1 WHERE candidate_id = '$unionVote'";
            if (!mysqli_query($conn, $query)) {
                $success = false; // If there's an error, mark success as false
            }
        }
        if ($deptRepVote) {
            $query = "UPDATE candidate SET votes = votes + 1 WHERE candidate_id = '$deptRepVote'";
            if (!mysqli_query($conn, $query)) {
                $success = false; // If there's an error, mark success as false
            }
        }
        if ($classRepVote) {
            $query = "UPDATE candidate SET votes = votes + 1 WHERE candidate_id = '$classRepVote'";
            if (!mysqli_query($conn, $query)) {
                $success = false; // If there's an error, mark success as false
            }
        }
        
        // Redirect or display success message
        if ($success) {
            // Mark the user as having voted
            $updateUser = "UPDATE users SET voted = 1 WHERE username = '$username'";
            mysqli_query($conn, $updateUser);
            
            header("Location: success.php"); // Redirect to a success page after voting
            exit;
        } else {
            $error = "There was an error updating the votes. Please try again.";
        }
    } else {
        $error = "Please vote in all categories.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote - Online Voting System</title>
    <style>
        /* Basic styling for the candidate layout */
        .candidate-box {
            display: flex; /* Use flexbox for side-by-side layout */
            flex-wrap: wrap; /* Allow wrapping for smaller screens */
            gap: 20px; /* Space between candidates */
        }
        .candidate-detail {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            width: 200px; /* Fixed width for uniformity */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9; /* Light background color */
        }
        .candidate-detail img {
            width: 100px; /* Fixed width for images */
            height: 100px; /* Fixed height for square images */
            object-fit: cover; /* Ensures the image covers the square area */
            border-radius: 8px; /* Rounded corners for images */
        }
        .candidate-detail button {
            margin-top: 10px; /* Space above the button */
            padding: 5px 10px; /* Button padding */
            background-color: #007BFF; /* Primary button color */
            color: white; /* Button text color */
            border: none; /* No border */
            border-radius: 5px; /* Rounded button */
            cursor: pointer; /* Pointer cursor on hover */
        }
        .candidate-detail button:hover {
            background-color: #0056b3; /* Darker on hover */
        }
        .candidate-row {
            display: flex; /* Use flexbox for rows */
            justify-content: flex-start; /* Align items to the left */
            gap: 20px; /* Space between candidates */
        }
    </style>
</head>
<body>
    <h2>Voting Page</h2>
    
    <?php if (!empty($error)) { ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST" action="">
        <!-- Main Union Candidates -->
        <div class="container">
            <h3>Main Union Candidates</h3>
            <div class="candidate-box">
                <?php if (mysqli_num_rows($resultUnionCandidates) > 0) { ?>
                    <div class="candidate-row">
                        <?php while ($unionCandidate = mysqli_fetch_assoc($resultUnionCandidates)) { ?>
                            <div class="candidate-detail">
                                <img src="<?php echo $unionCandidate['photo']; ?>" alt="<?php echo $unionCandidate['fullname']; ?>">
                                <p><strong>Full Name:</strong> <?php echo $unionCandidate['fullname']; ?></p>
                                <p><strong>Department:</strong> <?php echo $unionCandidate['department']; ?></p>
                                <p><strong>Batch:</strong> <?php echo $unionCandidate['batch'] ? $unionCandidate['batch'] : 'N/A'; ?></p>
                                <input type="radio" name="union_vote" value="<?php echo $unionCandidate['candidate_id']; ?>"> Vote
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <p>No candidates available for the Main Union.</p>
                <?php } ?>
            </div>
        </div>
        <br>

        <!-- Department Representative Candidates -->
        <div class="container">
            <h3>Department Representative Candidates</h3>
            <div class="candidate-box">
                <?php if (mysqli_num_rows($resultDeptRepCandidates) > 0) { ?>
                    <div class="candidate-row">
                        <?php while ($deptRepCandidate = mysqli_fetch_assoc($resultDeptRepCandidates)) { ?>
                            <div class="candidate-detail">
                                <img src="<?php echo $deptRepCandidate['photo']; ?>" alt="<?php echo $deptRepCandidate['fullname']; ?>">
                                <p><strong>Full Name:</strong> <?php echo $deptRepCandidate['fullname']; ?></p>
                                <p><strong>Department:</strong> <?php echo $deptRepCandidate['department']; ?></p>
                                <p><strong>Batch:</strong> <?php echo $deptRepCandidate['batch'] ? $deptRepCandidate['batch'] : 'N/A'; ?></p>
                                <input type="radio" name="dept_rep_vote" value="<?php echo $deptRepCandidate['candidate_id']; ?>"> Vote
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <p>No candidates available for Department Representative.</p>
                <?php } ?>
            </div>
        </div>
        <br>

        <!-- Class Representative Candidates -->
        <div class="container">
            <h3>Class Representative Candidates (<?php echo isset($department) ? "$department - $batch Batch, $year Year" : 'N/A'; ?>)</h3>
            <div class="candidate-box">
                <?php if (mysqli_num_rows($resultClassRepCandidates) > 0) { ?>
                    <div class="candidate-row">
                        <?php while ($classRepCandidate = mysqli_fetch_assoc($resultClassRepCandidates)) { ?>
                            <div class="candidate-detail">
                                <img src="<?php echo $classRepCandidate['photo']; ?>" alt="<?php echo $classRepCandidate['fullname']; ?>">
                                <p><strong>Full Name:</strong> <?php echo $classRepCandidate['fullname']; ?></p>
                                <p><strong>Department:</strong> <?php echo $classRepCandidate['department']; ?></p>
                                <p><strong>Batch:</strong> <?php echo $classRepCandidate['batch'] ? $classRepCandidate['batch'] : 'N/A'; ?></p>
                                <input type="radio" name="class_rep_vote" value="<?php echo $classRepCandidate['candidate_id']; ?>"> Vote
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <p>No candidates available for Class Representative.</p>
                <?php } ?>
            </div>
        </div>
        <br>

        <input type="submit" name="submit_votes" value="Submit Votes" />
    </form>

    <?php include 'footerall.php'; ?>
</body>
</html>
