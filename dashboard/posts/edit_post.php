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

// Get user information
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$full_name = $_SESSION['full_name'];

// Initialize variables
$post_id = $title = $content = "";
$error = "";
$success = "";

// Check if post ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $post_id = $_GET['id'];
    
    // Get post data from database
    $sql = "SELECT * FROM news WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $post = $result->fetch_assoc();
        $title = $post['title'];
        $content = $post['content'];
    } else {
        // Post not found, redirect back
        header("Location: view_posts.php");
        exit;
    }
    
    $stmt->close();
} else {
    // No post ID provided, redirect back
    header("Location: view_posts.php");
    exit;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    // Validate input
    if (empty($title) || empty($content)) {
        $error = "Title and content are required";
    } else {
        // Update post in database
        $sql = "UPDATE news SET title = ?, content = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $post_id);
        
        if ($stmt->execute()) {
            $success = "Post updated successfully!";
        } else {
            $error = "Error updating post: " . $stmt->error;
        }
        
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Post - BATU Dashboard</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background-color: #1c1c1c;
      color: #000;
    }

    .container {
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 220px;
      background-color: #1c1c1c;
      padding: 20px;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sidebar h2 {
      margin-bottom: 40px;
      font-size: 18px;
    }

    .menu a {
      color: #bbb;
      display: block;
      margin: 15px 0;
      text-decoration: none;
      transition: 0.3s;
    }

    .menu a.active, .menu a:hover {
      color: white;
      font-weight: bold;
    }

    .footer {
      font-size: 12px;
      color: gray;
    }

    .main {
      flex: 1;
      background-color: white;
      padding: 30px;
      overflow-y: auto;
    }

    .main h1 {
      font-size: 24px;
      margin-bottom: 20px;
      color: #333;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    
    .form-control {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 16px;
    }
    
    textarea.form-control {
      min-height: 200px;
    }
    
    .btn {
      padding: 10px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }
    
    .btn-primary {
      background-color: #1976d2;
      color: white;
    }
    
    .btn-secondary {
      background-color: #6c757d;
      color: white;
      margin-right: 10px;
    }
    
    .alert {
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 4px;
    }
    
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    
    .logout-link {
      color: #dc3545;
      text-decoration: none;
      margin-top: 10px;
      display: block;
    }
    
    .user-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      background-color: #f8f9fa;
      padding: 10px 15px;
      border-radius: 5px;
    }
    
    .user-details {
      display: flex;
      align-items: center;
    }
    
    .user-details span {
      font-weight: bold;
      margin-left: 10px;
    }
    
    .logout-btn {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <div>
        <h2>BATU Dashboard</h2>
        <nav class="menu">
          <a href="view_posts.php">View Posts</a>
          <a href="../index.php">New Post</a>
          <a href="../Courses.html">Courses</a>
          <?php if ($role === 'admin'): ?>
          <a href="../admin/index.php">Admin Panel</a>
          <?php endif; ?>
        </nav>
      </div>
      <div>
        <p class="footer">Main Dashboard</p>
        <a href="../../login/logout.php" class="logout-link">Logout</a>
      </div>
    </aside>

    <section class="main">
      <div class="user-info">
        <div class="user-details">
          <i class="fa fa-user-circle"></i>
          <span>Welcome, <?php echo htmlspecialchars($full_name); ?> (<?php echo htmlspecialchars($role); ?>)</span>
        </div>
        <a href="../../login/logout.php" class="logout-btn">Logout</a>
      </div>
      
      <h1>Edit Post</h1>
      
      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
      <?php endif; ?>
      
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php endif; ?>
      
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $post_id); ?>">
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
        </div>
        <div class="form-group">
          <label for="content">Content</label>
          <textarea class="form-control" id="content" name="content" required><?php echo htmlspecialchars($content); ?></textarea>
        </div>
        <div>
          <a href="view_posts.php" class="btn btn-secondary">Cancel</a>
          <button type="submit" class="btn btn-primary">Update Post</button>
        </div>
      </form>
    </section>
  </div>
</body>
</html> 