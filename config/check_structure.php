<?php
// Include database connection
require_once 'db_connect.php';

// Show table structure
$sql = "DESCRIBE news";
$result = $conn->query($sql);

if ($result) {
    echo "Table structure:\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error checking table structure: " . $conn->error;
}

$conn->close();
?> 