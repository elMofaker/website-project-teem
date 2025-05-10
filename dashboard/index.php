<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/index.php");
    exit;
}

// Include database connection
require_once '../config/db_connect.php';

// Get user information
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$full_name = $_SESSION['full_name'];

// Initialize variables
$post_message = "";
$post_success = "";
$post_error = "";

// Process post submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_content'])) {
    $post_content = mysqli_real_escape_string($conn, $_POST['post_content']);
    $post_title = mysqli_real_escape_string($conn, $_POST['post_title']);
    $post_type = mysqli_real_escape_string($conn, $_POST['post_type']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    
    if (empty($post_content) || empty($post_title)) {
        $post_error = "Title and content are required";
    } else {
        // Insert post into database
        $sql = "INSERT INTO news (title, content, type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $post_title, $post_content, $post_type);
        
        if ($stmt->execute()) {
            $post_success = "Post published successfully!";
            $post_message = "";
        } else {
            $post_error = "Error publishing post: " . $stmt->error;
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
  <title>BATU Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
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
      font-size: 20px;
      margin-bottom: 20px;
      color: #333;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .user-profile {
      display: flex;
      align-items: center;
    }

    .user-profile img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .toggle-switch {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .toggle-switch input {
      appearance: none;
      width: 40px;
      height: 20px;
      background: #ccc;
      border-radius: 20px;
      position: relative;
      cursor: pointer;
      transition: background 0.3s;
    }

    .toggle-switch input::before {
      content: '';
      position: absolute;
      width: 18px;
      height: 18px;
      background: white;
      border-radius: 50%;
      top: 1px;
      left: 1px;
      transition: 0.3s;
    }

    .toggle-switch input:checked {
      background: #1976d2;
    }

    .toggle-switch input:checked::before {
      transform: translateX(20px);
    }

    .tabs {
      display: flex;
      margin-bottom: 10px;
    }

    .tabs button {
      padding: 10px 15px;
      margin-right: 5px;
      border: none;
      background: #e0e0e0;
      cursor: pointer;
      transition: 0.2s;
    }

    .tabs button:hover, .tabs button.active {
      background: #ccc;
      font-weight: bold;
    }

    .upload-btn {
      background: #1976d2;
      color: white;
      border: none;
      padding: 10px 15px;
      float: right;
      margin-top: -42px;
      cursor: pointer;
      border-radius: 4px;
    }

    textarea {
      width: 100%;
      height: 100px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin: 20px 0;
    }

    .post-btn {
      background: black;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 4px;
      float: right;
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
          <a href="posts/view_posts.php">View Posts</a>
          <a href="index.php" class="active">New Post</a>
          <a href="Courses.html">Courses</a>
          <?php if ($role === 'admin'): ?>
          <a href="admin/index.php">Admin Panel</a>
          <?php endif; ?>
        </nav>
      </div>
      <div>
        <p class="footer">Main Dashboard</p>
        <a href="../login/logout.php" class="logout-link">Logout</a>
      </div>
    </aside>

    <section class="main">
      <div class="user-info">
        <div class="user-details">
          <i class="fa fa-user-circle"></i>
          <span>Welcome, <?php echo htmlspecialchars($full_name); ?> (<?php echo htmlspecialchars($role); ?>)</span>
        </div>
        <a href="../login/logout.php" class="logout-btn">Logout</a>
      </div>
      
      <h1>New Post</h1>
      
      <?php if (!empty($post_success)): ?>
        <div class="alert alert-success"><?php echo $post_success; ?></div>
      <?php endif; ?>
      
      <?php if (!empty($post_error)): ?>
        <div class="alert alert-danger"><?php echo $post_error; ?></div>
      <?php endif; ?>
      
      <div class="top-bar">
        <div class="user-profile">
          <img src="https://via.placeholder.com/40" alt="User">
          <strong><?php echo htmlspecialchars($full_name); ?></strong>
        </div>
        <?php if ($role === 'admin' || $role === 'faculty'): ?>
        <div class="toggle-switch">
          <span>Post As Admin</span>
          <input type="checkbox" name="is_admin" form="post-form">
        </div>
        <?php endif; ?>
      </div>

      <form id="post-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="tabs">
          <button type="button" class="post-tab active" data-type="post" onclick="setPostType('post')">Post</button>
          <button type="button" class="post-tab" data-type="news" onclick="setPostType('news')">News</button>
          <button type="button" class="post-tab" data-type="announcement" onclick="setPostType('announcement')">Announcement</button>
          <button type="button" class="upload-btn">Add Photo / Video</button>
        </div>
        <input type="hidden" name="post_type" id="post_type" value="post">
        
        <div class="form-group mb-3">
            <input type="text" 
                   class="form-control" 
                   name="post_title" 
                   placeholder="Enter title" 
                   required 
                   style="
                    margin-top: 20px;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    font-size: 16px;
                   ">
        </div>
        
        <textarea name="post_content" 
                  placeholder="What's on your Mind ?" 
                  required><?php echo htmlspecialchars($post_message); ?></textarea>
        <button type="submit" class="post-btn">Post</button>
      </form>
    </section>
  </div>
  
  <script>
    // Handle post type selection
    function setPostType(type) {
      document.getElementById('post_type').value = type;
      
      // Update active tab
      document.querySelectorAll('.post-tab').forEach(tab => {
        if (tab.getAttribute('data-type') === type) {
          tab.classList.add('active');
        } else {
          tab.classList.remove('active');
        }
      });
    }
    
    // Initialize with default type
    document.addEventListener('DOMContentLoaded', function() {
      setPostType('post');
    });
  </script>
</body>
</html> 