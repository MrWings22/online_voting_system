<?php
require_once 'header_back.php';
renderHeader('index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - College Online Voting System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        main {
            padding: 20px;
        }

        h1 {
            color: #003366;
        }

        .help-section {
            margin-bottom: 30px;
        }

        .help-section h2 {
            color: #1e9bc8;
            font-size: 24px;
        }

        .help-section p {
            font-size: 18px;
            color: #333;
        }

        footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 20px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

    </style>
</head>
<body>
    <main>
        <section class="help-section">
            <h2>How to Register</h2>
            <p>To register for the online voting system, click the "New user? Register here" link on the home page. Fill out the registration form with your name, and other required details. Once registered, you will receive a confirmation notification.</p>
        </section>

        <section class="help-section">
            <h2>How to Log In</h2>
            <p>Click the "Login" link on the homepage. Enter your username and password to access the voting system.</p>
        </section>

        <section class="help-section">
            <h2>How to Vote</h2>
            <p>After logging in, navigate to the "Voting" section. You will see a list of candidates. Select the candidate you wish to vote for and click the "Submit Vote" button. You will receive a confirmation that your vote has been recorded.</p>
        </section>
        <a href="contctadmin.php">contact if any issues</a>
    </main>

    <footer>
        <p>&copy; 2024 BVM Holy Cross College Cherpunkal. All Rights Reserved.</p>
    </footer>

</body>
</html>
