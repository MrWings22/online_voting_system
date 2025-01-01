<?php
include 'headerindex.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- Main Content -->
    <div class="main-container">
        <!-- Text Section -->
        <div class="text-section">
            <h1>Welcome to the College Voting System</h1>
            <p>Your voice matters! This system allows students to participate in a secure, easy, and transparent election process. Whether you are a voter or a candidate, cast your vote and shape the future of the college.</p>
            <a href="login.php" class="btn">Get Start</a>
            <a href="userregistration.php">New user? Register here</a>
        </div>

        <!-- Image Section -->
        <div class="image-section">
            <img src="uploads/6974903_4428.svg" alt="Internet Voting Illustration">
        </div>
    </div>
    <!-- Chatbot Interface -->
    <div class="chatbot-icon">
        <img src="uploads/botgif.gif" alt="Chatbot Icon">
    </div>

    <div class="chatbot-container">
        <div class="chatbot-header">Chatbot</div>
        <div class="chatbot-body" id="chatbotBody"></div>
        <div class="chatbot-footer">
            <input type="text" id="userInput" placeholder="Ask a question...">
            <button id="sendButton">Send</button><br>
        </div>
        <div class="faq-section">
        <h3>Frequently Asked Questions:</h3>
        <button class="faq-btn" data-question="What is the voting process?">What is the voting process?</button>
        <button class="faq-btn" data-question="How can I register?">How can I register?</button>
        <button class="faq-btn" data-question="What are the eligibility criteria?">What are the eligibility criteria?</button>
        </div>

    </div>

    <!-- Include JS and CSS -->
    <link rel="stylesheet" href="chatbot/chatbot.css">
    <script src="chatbot/chatbot.js"></script>
</body>
</html>
