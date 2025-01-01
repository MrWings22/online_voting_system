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
    exit;
}

// Get the user's department, year, and batch from the `users` table
$department = $user['department'];
$year = $user['year'];
$batch = $user['batch'];

// Ensure election_id is available in the session
if (isset($_POST['election_id'])) {
    $_SESSION['election_id'] = $_POST['election_id'];
}
$election_id = $_SESSION['election_id'] ?? null;

// Fetch only approved candidates for the Main Union in the selected election
$queryUnionCandidates = "SELECT * FROM candidate WHERE position = 'Main Union' AND approved = 1 AND election = '$election_id'";
$resultUnionCandidates = mysqli_query($conn, $queryUnionCandidates);

// Fetch only approved candidates for the Department Representative based on the user's department and selected election
$queryDeptRepCandidates = "SELECT * FROM candidate WHERE department = '$department' AND position = 'Department Representative' AND approved = 1 AND election = '$election_id'";
$resultDeptRepCandidates = mysqli_query($conn, $queryDeptRepCandidates);

// Fetch only approved candidates for Class Representative based on the user's department, batch, year, and selected election
$queryClassRepCandidates = "SELECT * FROM candidate WHERE department = '$department' AND batch = '$batch' AND year = '$year' AND position = 'Class Representative' AND approved = 1 AND election = '$election_id'";
$resultClassRepCandidates = mysqli_query($conn, $queryClassRepCandidates);

// Handle voting submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_votes'])) {
    $unionVote = $_POST['union_vote'] ?? null;
    $deptRepVote = $_POST['dept_rep_vote'] ?? null;
    $classRepVote = $_POST['class_rep_vote'] ?? null;

    $hasUnionCandidates = mysqli_num_rows($resultUnionCandidates) > 0;
    $hasDeptRepCandidates = mysqli_num_rows($resultDeptRepCandidates) > 0;
    $hasClassRepCandidates = mysqli_num_rows($resultClassRepCandidates) > 0;

    $error = '';
    if (
        ($hasUnionCandidates && !$unionVote) ||
        ($hasDeptRepCandidates && !$deptRepVote) ||
        ($hasClassRepCandidates && !$classRepVote)
    ) {
        $error = "Please vote in all categories with candidates.";
    } else {
        $success = true;

        // Handle votes for Main Union
        if ($unionVote) {
            $updateQuery = "UPDATE candidate SET votes = votes + 1 WHERE candidate_id = '$unionVote'";
            $insertVoteQuery = "INSERT INTO votes (user_id, username, vote_time, candidate_id, category, position, election_id)
                                VALUES ('" . $user['user_id'] . "', '$username', NOW(), '$unionVote', 'Main Union', 
                                (SELECT position FROM candidate WHERE candidate_id = '$unionVote'), '$election_id')";

            if (!mysqli_query($conn, $updateQuery) || !mysqli_query($conn, $insertVoteQuery)) {
                $success = false;
            }
        }

        // Handle votes for Department Representative
        if ($deptRepVote) {
            $updateQuery = "UPDATE candidate SET votes = votes + 1 WHERE candidate_id = '$deptRepVote'";
            $insertVoteQuery = "INSERT INTO votes (user_id, username, vote_time, candidate_id, category, position, election_id)
                                VALUES ('" . $user['user_id'] . "', '$username', NOW(), '$deptRepVote', 'Department Representative', 
                                (SELECT position FROM candidate WHERE candidate_id = '$deptRepVote'), '$election_id')";

            if (!mysqli_query($conn, $updateQuery) || !mysqli_query($conn, $insertVoteQuery)) {
                $success = false;
            }
        }

        // Handle votes for Class Representative
        if ($classRepVote) {
            $updateQuery = "UPDATE candidate SET votes = votes + 1 WHERE candidate_id = '$classRepVote'";
            $insertVoteQuery = "INSERT INTO votes (user_id, username, vote_time, candidate_id, category, position, election_id)
                                VALUES ('" . $user['user_id'] . "', '$username', NOW(), '$classRepVote', 'Class Representative', 
                                (SELECT position FROM candidate WHERE candidate_id = '$classRepVote'), '$election_id')";

            if (!mysqli_query($conn, $updateQuery) || !mysqli_query($conn, $insertVoteQuery)) {
                $success = false;
            }
        }

        if ($success) {
            $updateUserQuery = "UPDATE users SET voted = 1 WHERE username = '$username'";
            if (mysqli_query($conn, $updateUserQuery)) {
                header("Location: success.php");
                exit;
            } else {
                $error = "Error updating user voting status.";
            }
        } else {
            $error = "There was an error updating the votes. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote - Online Voting System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .candidate-box {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .candidate-detail {
            border: 1px solid #ccc;
            padding: 15px;
            text-align: center;
            width: 100%;
            max-width: 300px;
            border-radius: 8px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
            background-color: #ffffff;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .candidate-detail:hover {
            transform: scale(1.05);
            box-shadow: 4px 4px 12px rgba(0, 0, 0, 0.3);
        }
        .candidate-detail img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .candidate-detail .vote-btn {
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .candidate-detail .vote-btn:hover {
            background-color: #0056b3;
        }
        .candidate-detail .icon {
            font-size: 20px;
            color: #6c757d;
            margin-right: 5px;
        }
        .submit-btn {
            margin-top: 20px;
            padding: 10px 30px;
            font-size: 18px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .submit-btn:hover {
            background-color: #218838;
            box-shadow: 2px 2px 10px rgba(0, 128, 0, 0.4);
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h2 class="text-center text-primary mb-4"><i class="fas fa-vote-yea"></i> Voting Page</h2>
        
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <form method="POST" action="" onsubmit="return confirmVotes();">
            <!-- Main Union Candidates -->
            <div class="mb-5">
                <h3 class="text-secondary"><i class="fas fa-users"></i> Main Union Candidates</h3>
                <div class="candidate-box">
                    <?php if (mysqli_num_rows($resultUnionCandidates) > 0) { ?>
                        <?php while ($unionCandidate = mysqli_fetch_assoc($resultUnionCandidates)) { ?>
                            <div class="candidate-detail">
                                <img src="<?php echo $unionCandidate['photo'] ?: 'placeholder.jpg'; ?>" alt="<?php echo $unionCandidate['fullname'] ?: 'No Name'; ?>">
                                <p><strong><i class="fas fa-user icon"></i> <?php echo $unionCandidate['fullname'] ?: 'No Name'; ?></strong></p>
                                <p><i class="fas fa-building icon"></i> Department: <?php echo $unionCandidate['department'] ?: 'N/A'; ?></p>
                                <p><i class="fas fa-calendar icon"></i> Batch: <?php echo $unionCandidate['batch'] ?: 'N/A'; ?></p>
                                <input type="radio" name="union_vote" value="<?php echo $unionCandidate['candidate_id']; ?>" class="form-check-input" data-category="Main Union" data-name="<?php echo $unionCandidate['fullname']; ?>"> Vote
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-center text-muted">No candidates available for the Main Union.</p>
                    <?php } ?>
                </div>
            </div>

            <!-- Department Representative Candidates -->
            <div class="mb-5">
                <h3 class="text-secondary"><i class="fas fa-chalkboard-teacher"></i> Department Representative Candidates</h3>
                <div class="candidate-box">
                    <?php if (mysqli_num_rows($resultDeptRepCandidates) > 0) { ?>
                        <?php while ($deptRepCandidate = mysqli_fetch_assoc($resultDeptRepCandidates)) { ?>
                            <div class="candidate-detail">
                                <img src="<?php echo $deptRepCandidate['photo'] ?: 'placeholder.jpg'; ?>" alt="<?php echo $deptRepCandidate['fullname'] ?: 'No Name'; ?>">
                                <p><strong><i class="fas fa-user icon"></i> <?php echo $deptRepCandidate['fullname'] ?: 'No Name'; ?></strong></p>
                                <p><i class="fas fa-building icon"></i> Department: <?php echo $deptRepCandidate['department'] ?: 'N/A'; ?></p>
                                <p><i class="fas fa-calendar icon"></i> Batch: <?php echo $deptRepCandidate['batch'] ?: 'N/A'; ?></p>
                                <input type="radio" name="dept_rep_vote" value="<?php echo $deptRepCandidate['candidate_id']; ?>" class="form-check-input" data-category="Department Representative" data-name="<?php echo $deptRepCandidate['fullname']; ?>"> Vote
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-center text-muted">No candidates available for Department Representative.</p>
                    <?php } ?>
                </div>
            </div>

            <!-- Class Representative Candidates -->
            <div class="mb-5">
                <h3 class="text-secondary"><i class="fas fa-school"></i> Class Representative Candidates</h3>
                <div class="candidate-box">
                    <?php if (mysqli_num_rows($resultClassRepCandidates) > 0) { ?>
                        <?php while ($classRepCandidate = mysqli_fetch_assoc($resultClassRepCandidates)) { ?>
                            <div class="candidate-detail">
                                <img src="<?php echo $classRepCandidate['photo'] ?: 'placeholder.jpg'; ?>" alt="<?php echo $classRepCandidate['fullname'] ?: 'No Name'; ?>">
                                <p><strong><i class="fas fa-user icon"></i> <?php echo $classRepCandidate['fullname'] ?: 'No Name'; ?></strong></p>
                                <p><i class="fas fa-building icon"></i> Department: <?php echo $classRepCandidate['department'] ?: 'N/A'; ?></p>
                                <p><i class="fas fa-calendar icon"></i> Batch: <?php echo $classRepCandidate['batch'] ?: 'N/A'; ?></p>
                                <input type="radio" name="class_rep_vote" value="<?php echo $classRepCandidate['candidate_id']; ?>" class="form-check-input" data-category="Class Representative" data-name="<?php echo $classRepCandidate['fullname']; ?>"> Vote
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-center text-muted">No candidates available for Class Representative.</p>
                    <?php } ?>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" name="submit_votes" class="submit-btn">Submit Votes <i class="fas fa-paper-plane"></i></button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmVotes() {
            const selectedVotes = document.querySelectorAll('input[type="radio"]:checked');
            if (selectedVotes.length === 0) {
                alert("Please select your votes.");
                return false;
            }

            let confirmationMessage = "You have selected the following candidates:\n\n";
            selectedVotes.forEach(vote => {
                confirmationMessage += `Category: ${vote.getAttribute('data-category')}, Candidate: ${vote.getAttribute('data-name')}\n`;
            });

            confirmationMessage += "\nDo you want to submit your votes?";
            return confirm(confirmationMessage);
        }
    </script>
</body>
</html>
