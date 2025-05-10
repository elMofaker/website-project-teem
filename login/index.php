<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/index.php");
    exit;
}

// Initialize variables
$username = $password = "";
$error = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once '../config/db_connect.php';
    
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password";
    } else {
        // Check user in database
        $sql = "SELECT id, username, password, role, full_name FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a new session
                session_start();
                
                // Store data in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                
                // Redirect to dashboard
                header("Location: ../dashboard/index.php");
                exit;
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
        
        $stmt->close();
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Smooch+Sans:wght@700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="login-box">
      <h1>Welcome</h1>
      <p>We are glad to see you back with us</p>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="input-icon-wrapper">
          <i class="fa fa-user icon-left"></i>
          <input type="text" class="custom-input" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required />
        </div>
        <div class="input-icon-wrapper">
          <i class="fa fa-lock icon-left"></i>
          <input type="password" class="custom-input" name="password" placeholder="Password" required />
        </div>
        <button type="submit">NEXT</button>
      </form>
      <div class="login-others">
        <button class="login-btn">Login</button> with Others
      </div>
      <div class="social-login">
        <button class="google-btn">
          <img src="google 1.png" alt="Google icon" />
          Login with <strong>google</strong>
        </button>
        <button class="facebook-btn">
          <img src="https://upload.wikimedia.org/wikipedia/commons/0/05/Facebook_Logo_%282019%29.png" alt="Facebook icon" />
          Login with <strong>Facebook</strong>
        </button>
      </div>
      <div class="mt-3 text-center">
        <a href="../index.html">Back to Home</a>
      </div>
    </div>
    <div class="illustration">
      <img src="Login.svg" alt="Illustration" />
    </div>
  </div>
</body>
</html> 