<?php
include 'headerindex.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
   
    <link rel="stylesheet" href="index.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
</head>
<body>
    <div class="index-welcome">
        <h1>Welcome to the College Online Voting System</h1>
        <p>Your voice matters! This system allows students to participate in a secure, easy, and transparent election process. Whether you are a voter or a candidate, cast your vote and shape the future of the college.</p>
    </div>
    <div class="container">
    <a href="login.php" class="btn"><i class="fas fa-sign-in-alt"></i> Login</a>
    <br>
    <a href="userregistration.php" class="register-link"><i class="fas fa-user-plus"></i> New user? Register here</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
