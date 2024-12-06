<?php
session_start();
include 'db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: contctadmin.php");
        exit;
    }

    // Insert data into the 'contact' table
    $query = "INSERT INTO contact (name, message, created_at) VALUES ('$name', '$message', NOW())";

    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Your message has been sent successfully!";
    } else {
        $_SESSION['error'] = "Failed to send your message. Please try again.";
    }

    // Redirect back to the contact page
    header("Location: contctadmin.php");
    exit;
} else {
    // Redirect if accessed without POST data
    header("Location: contctadmin.php");
    exit;
}
?>
