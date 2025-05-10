<?php
// Start the session
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login/index.php");
    exit;
}

// Include database connection
require_once '../../config/db_connect.php';

// Check if post ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $post_id = $_GET['id'];
    
    // Delete post from database
    $sql = "DELETE FROM news WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    
    if ($stmt->execute()) {
        // Redirect back to view posts page with success message
        header("Location: view_posts.php?deleted=true");
    } else {
        // Redirect back with error message
        header("Location: view_posts.php?error=true");
    }
    
    $stmt->close();
} else {
    // Redirect back if no valid ID provided
    header("Location: view_posts.php");
}

$conn->close();
?> 