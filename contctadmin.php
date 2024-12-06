<?php
require_once 'header_back.php';

// Start the session
session_start();

// Get the previous page URL using HTTP_REFERER, or fallback to index.php
$previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

// Render the header with the dynamic back link
renderHeader($previousPage);

// Include database connection
include 'db_connection.php';

// Define a variable for the alert message
$alertMessage = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_message'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $previousPage = htmlspecialchars($_POST['previous_page']); // Get the hidden input value

    if (!empty($name) && !empty($message)) {
        $query = "INSERT INTO contact (name, message, created_at) VALUES ('$name', '$message', NOW())";
        if (mysqli_query($conn, $query)) {
            $alertMessage = "Your message has been sent to the admin.";
        } else {
            $alertMessage = "Failed to send your message. Please try again.";
        }
    } else {
        $alertMessage = "All fields are required.";
    }

    // Store the alert message in the session
    $_SESSION['alertMessage'] = $alertMessage;

    // Redirect back to the previous page
    echo "<script>
        alert('$alertMessage');
        window.location.href = '$previousPage';
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, textarea, button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Contact Admin</h2>
        <form method="POST" action="contctadmin.php">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required placeholder="Enter your name">

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" required placeholder="Describe your issue"></textarea>

            <input type="hidden" name="previous_page" value="<?= htmlspecialchars($previousPage) ?>">

            <button type="submit" name="submit_message">Send Message</button>
        </form>
    </div>
</body>
</html>
