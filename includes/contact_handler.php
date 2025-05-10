<?php
// Contact form handler
require_once 'config/db_connect.php';

// Initialize response array
$response = array(
    'status' => 'error',
    'message' => 'An error occurred'
);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $full_name = mysqli_real_escape_string($conn, $_POST['fullName']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    // Validate input
    if (empty($full_name) || empty($subject) || empty($message)) {
        $response['message'] = "All fields are required";
    } else {
        // Insert message into database
        $sql = "INSERT INTO contact_messages (full_name, subject, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $full_name, $subject, $message);
        
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = "Your message has been sent successfully!";
        } else {
            $response['message'] = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// If accessed directly without POST
$response['message'] = "Invalid request method";
header('Content-Type: application/json');
echo json_encode($response);
?> 