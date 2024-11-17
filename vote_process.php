<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $election_id = $_POST['election_id'];
    $username = $_SESSION['username'];
    
    // Fetch the user details
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['user_id'];
    
    // Check if the user has already voted in this election
    $query = "SELECT * FROM voters WHERE user_id = $user_id AND election_id = $election_id";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "You have already voted in this election.";
    } else {
        // Proceed to candidate selection for voting
        header("Location: candidate_selection.php?election_id=$election_id");
    }
}
?>
