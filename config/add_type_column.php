<?php
// Include database connection
require_once 'db_connect.php';

// Add type column if it doesn't exist
$sql = "SHOW COLUMNS FROM news LIKE 'type'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Column doesn't exist, so add it
    $sql = "ALTER TABLE news ADD COLUMN type ENUM('post', 'news', 'announcement') NOT NULL DEFAULT 'post' AFTER content";
    if ($conn->query($sql) === TRUE) {
        echo "Type column added successfully";
    } else {
        echo "Error adding type column: " . $conn->error;
    }
} else {
    echo "Type column already exists";
}

$conn->close();
?> 