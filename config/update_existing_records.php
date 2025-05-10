<?php
// Include database connection
require_once 'db_connect.php';

// Update any NULL type values to 'post'
$sql = "UPDATE news SET type = 'post' WHERE type IS NULL";
if ($conn->query($sql) === TRUE) {
    echo "Existing records updated successfully";
} else {
    echo "Error updating records: " . $conn->error;
}

$conn->close();
?> 