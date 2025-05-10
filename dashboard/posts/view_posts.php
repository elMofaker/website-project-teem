<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
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

// Get all posts from database
$sql = "SELECT * FROM news ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Posts - BATU Dashboard</title>
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

    .post {
      border: 1px solid #e0e0e0;
      border-radius: 5px;
      padding: 20px;
      margin-bottom: 20px;
      background-color: #f9f9f9;
    }

    .post-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .post-title {
      font-size: 18px;
      font-weight: bold;
    }

    .post-date {
      color: #777;
      font-size: 14px;
    }

    .post-content {
      margin-bottom: 15px;
      line-height: 1.5;
    }

    .post-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    .post-actions button {
      padding: 5px 10px;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    .edit-btn {
      background-color: #4caf50;
      color: white;
    }

    .delete-btn {
      background-color: #f44336;
      color: white;
    }

    .no-posts {
      text-align: center;
      padding: 50px;
      color: #777;
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

    .post-type {
      font-size: 14px;
      color: #666;
      margin-left: 10px;
      font-weight: normal;
    }
  </style>
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <div>
        <h2>BATU Dashboard</h2>
        <nav class="menu">
          <a href="view_posts.php" class="active">View Posts</a>
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
      
      <h1>All Posts</h1>
      
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <div class="post">
            <div class="post-header">
              <div class="post-title">
                <?php echo htmlspecialchars($row['title']); ?>
                <span class="post-type">[<?php echo ucfirst(htmlspecialchars($row['type'])); ?>]</span>
              </div>
              <div class="post-date"><?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></div>
            </div>
            <div class="post-content">
              <?php echo nl2br(htmlspecialchars($row['content'])); ?>
            </div>
            <?php if ($role === 'admin'): ?>
            <div class="post-actions">
              <button class="edit-btn" onclick="editPost(<?php echo $row['id']; ?>)">Edit</button>
              <button class="delete-btn" onclick="deletePost(<?php echo $row['id']; ?>)">Delete</button>
            </div>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="no-posts">
          <h3>No posts found</h3>
          <p>Be the first to create a post!</p>
        </div>
      <?php endif; ?>
    </section>
  </div>
  
  <script>
    function editPost(id) {
      if (confirm('Do you want to edit this post?')) {
        window.location.href = 'edit_post.php?id=' + id;
      }
    }
    
    function deletePost(id) {
      if (confirm('Are you sure you want to delete this post?')) {
        window.location.href = 'delete_post.php?id=' + id;
      }
    }
  </script>
</body>
</html> 