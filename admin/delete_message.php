<?php
session_start();
include 'db_connection.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);

    // Delete the message with the given ID
    $query = "DELETE FROM contact WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Message deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete the message. Please try again.";
    }

    header("Location: admin_view_messages.php"); // Redirect back to the admin page
    exit;
}
?>
