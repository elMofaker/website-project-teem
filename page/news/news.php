<?php
// Include database connection
require_once '../../config/db_connect.php';

// Get all posts ordered by creation date
$sql = "SELECT * FROM news ORDER BY created_at DESC";
$result = $conn->query($sql);

// Store posts in array
$posts = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="news.css">
    <title>Borg El Arab Technological University - News</title>
</head>

<body>
    <!-- start nav -->
    <header style="position: static;">
        <div class="logo">
            <img src="../../resource/LogoNav.svg" alt="University Logo" />
        </div>
        <nav>
            <ul id="main-nav-list">
                <li>
                    <a href="../../index.php">Home</a>
                </li>
                <li>
                    <a href="../faculties/faculties.html">Faculties</a>
                </li>
                <li class="active"><a href="#">News</a></li>
                <li><a href="../about-the-collage/about-the-collage.html">About The Collage</a></li>
            </ul>
        </nav>
        <button class="burger-menu" id="burger-menu" aria-label="Toggle Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>
    <!-- end nav -->
    <div class="news py-5">
        <!-- Main Content -->
        <section class="all-posts-section container">
            <div class="all-posts-header py-3">
                <h4 class="fw-bold">OverView :</h4>
            </div>
            <div class="container pb-5">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <article class="post-card announcement-card">
                            <div class="post-header">
                                <div class="author-info d-flex justify-content-center align-items-center">
                                    <i class="fas fa-user-circle user-icon"></i>
                                    <h3 class="post-author">Admin</h3>
                                </div>
                            </div>

                            <div class="post-content">
                                <h4 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h4>
                                <p class=""><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                            </div>

                            <div class="post-footer">
                                <div class="post-meta-left">
                                    <span class="post-badge fw-bold" 
                                    style="
                                    background: <?php echo $post['type'] === 'announcement' ? '#dc3545' : ($post['type'] === 'news' ? '#0d6efd' : '#000'); ?>;
                                    color: white;
                                    padding: 3px 5px;
                                    border-radius: 8px;
                                    font-size: 0.8rem;">
                                    <?php echo ucfirst(htmlspecialchars($post['type'])); ?>
                                    </span>
                                </div>
                                <div class="post-meta-right">
                                    <div class="post-date">
                                        <i class="fas fa-calendar"></i>
                                        <span class="fw-bold"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">No posts available at the moment.</div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- start footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h4>Pref About BATU</h4>
                <p>Lorem ipsum posuere cursus amet dolor <br>sit velit sem in rhoncus sagittis odio <br> volutpat aenean
                    in gravida diam amet<br> vulputate tincidunt proin fusce ultrices <br>fermentum vivamus eget</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
                    <a href="#"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
                    <a href="#"><i class="fab fa-youtube"></i> Youtube</a>
                </div>
            </div>

            <div class="footer-section">
                <h4>Our Faciltes</h4>
                <div class="facility">
                    <p>College of Industry<br> and Energy <br>Technology</p>
                    <p>Faculty of Health <br>Sciences <br> Technology </p>
                </div>
            </div>

            <div class="footer-section">
                <h4>Explore More</h4>
                <ul>
                    <li><a1 href="#">News</a1></li>
                    <li><a1 href="#">Site map</a1></li>
                    <li><a1 href="#">About us</a1></li>
                    <li><a1 href="#">Join us</a1></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Contact us</h4>
                <p>batu@contact.com</p>
                <p>+1 (555) 123-4567</p>
            </div>
        </div>
    </footer>
    <!-- end footer -->
    <script src="../../script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php $conn->close(); ?> 