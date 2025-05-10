<?php
// Include database connection
require_once 'db_connect.php';

// Drop the existing news table
$sql = "DROP TABLE IF EXISTS news";
$conn->query($sql);

// Create news table with correct structure
$sql = "CREATE TABLE news (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    type ENUM('post', 'news', 'announcement') NOT NULL DEFAULT 'post',
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "News table recreated successfully with correct structure";
} else {
    echo "Error recreating news table: " . $conn->error;
}

$conn->close();
?> 