<?php
// Database initialization script
// Connect to MySQL server without selecting a database
$conn = new mysqli('localhost', 'root', '');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS university_db";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
    exit;
}

// Select the database
$conn->select_db('university_db');

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'student', 'faculty') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully or already exists<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Create contact_messages table
$sql = "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Contact messages table created successfully or already exists<br>";
} else {
    echo "Error creating contact messages table: " . $conn->error . "<br>";
}

// Drop existing news table to ensure clean structure
$sql = "DROP TABLE IF EXISTS news";
$conn->query($sql);

// Create news table with updated structure
$sql = "CREATE TABLE news (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    type ENUM('post', 'news', 'announcement') NOT NULL DEFAULT 'post',
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "News table created successfully with updated structure<br>";
} else {
    echo "Error creating news table: " . $conn->error . "<br>";
}

// Insert default admin user if not exists
$default_password = password_hash('admin123', PASSWORD_DEFAULT);
$sql = "INSERT IGNORE INTO users (username, password, email, full_name, role) 
        VALUES ('admin', '$default_password', 'admin@university.edu', 'Administrator', 'admin')";

if ($conn->query($sql) === TRUE) {
    echo "Default admin user created or already exists<br>";
} else {
    echo "Error creating default admin user: " . $conn->error . "<br>";
}

// Insert some sample news posts
$sample_posts = [
    [
        'title' => 'Welcome to BATU',
        'content' => 'Welcome to Borg El Arab Technological University. We are excited to start this new academic year!',
        'type' => 'announcement'
    ],
    [
        'title' => 'New Research Center Opening',
        'content' => 'We are pleased to announce the opening of our new research center dedicated to technological innovation.',
        'type' => 'news'
    ],
    [
        'title' => 'Student Registration Open',
        'content' => 'Registration for the new semester is now open. Please visit the registration office to complete your enrollment.',
        'type' => 'post'
    ]
];

$stmt = $conn->prepare("INSERT INTO news (title, content, type) VALUES (?, ?, ?)");

foreach ($sample_posts as $post) {
    $stmt->bind_param("sss", $post['title'], $post['content'], $post['type']);
    if ($stmt->execute()) {
        echo "Sample post '{$post['title']}' created successfully<br>";
    } else {
        echo "Error creating sample post: " . $stmt->error . "<br>";
    }
}

$stmt->close();
$conn->close();
echo "Database initialization complete!";
?> 