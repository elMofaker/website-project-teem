<?php
// Start session
session_start();

// Include database connection
require_once 'config/db_connect.php';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_role = $is_logged_in ? $_SESSION['role'] : '';

// Get latest news for display
$news = array();
$sql = "SELECT * FROM news ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $news[] = $row;
    }
}

// Process contact form submission via AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contact_submit'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['fullName']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    $response = array('status' => 'error', 'message' => 'An error occurred');
    
    if (empty($full_name) || empty($subject) || empty($message)) {
        $response['message'] = "All fields are required";
    } else {
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Borg El Arab Technological University</title>
</head>

<body>
    <!-- start nav -->
    <header>
        <div class="logo">
            <img src="resource/LogoNav.svg" alt="University Logo" />
        </div>
        <nav>
            <ul id="main-nav-list">
                <li class="active">
                    <a href="#">Home</a>
                </li>
                <li>
                    <a href="page/faculties/faculties.html">Faculties</a>
                </li>
                <li><a href="page/news/news.php">News</a></li>
                <li><a href="page/about-the-collage/about-the-collage.html">About The Collage</a></li>
                <li><a href="login/index.php">Login</a></li>
            </ul>
        </nav>
        <button class="burger-menu" id="burger-menu" aria-label="Toggle Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>
    <!-- end nav -->
    <!-- start hero -->
    <main>
        <div class="slider-container">
            <div class="slider">
                <div class="slide">
                    <img src="resource/College1.svg" alt="University Campus Image 1" />
                    <div class="slide-content">
                        <h1>Welcome To Borg El Arab Technological University</h1>
                        <p>
                            Where you will achieve your dreams and goals. It's always starts
                            with the first step until you become the one you are looking for
                        </p>
                    </div>
                </div>
                <div class="slide">
                    <img src="resource/College2.svg" alt="University Campus Image 2" />
                    <div class="slide-content">
                        <h1>Welcome To Borg El Arab Technological University</h1>
                        <p> Where you will achieve your dreams and goals. It's always starts
                            with the first step until you become the one you are looking for</p>
                    </div>
                </div>
                <div class="slide">
                    <img src="resource/College3.svg" alt="University Campus Image 3" />
                    <div class="slide-content">
                        <h1>Welcome To Borg El Arab Technological University</h1>
                        <p> Where you will achieve your dreams and goals. It's always starts
                            with the first step until you become the one you are looking for</p>
                    </div>
                </div>
            </div>
            <button class="slider-btn prev-btn" aria-label="Previous Slide">&#10094;</button>
            <button class="slider-btn next-btn" aria-label="Next Slide">&#10095;</button>
            <div class="slider-dots"></div>
        </div>
    </main>
    <!-- end hero -->
    <!-- start about -->
    <section id="about-collage">
        <div class="container">
            <h2 class="section-title  mb-4 fw-bold text-center">About The Collage</h2>
            <div class="cards">
                <div class="card">
                    <div class="icon-wrapper">
                        <img src="resource/about-1.svg" alt="Faculties icon" class="icon">
                    </div>
                    <h3 class="card-title">+2 Faculties</h3>
                    <p class="card-text">
                        Lorem ipsum cursus eget nunc fermentum pharetra ullamcorper lorem sed turpis in sed pretium
                        magna risus metus sit malesuada adipiscing ac at aliquam eget condimentum risus pulvinar platea
                        ac magna laoreet.
                    </p>
                </div>
                <div class="card">
                    <div class="icon-wrapper">
                        <img src="resource/about-2.svg" alt="Program icon" class="icon">
                    </div>
                    <h3 class="card-title">+9 Program</h3>
                    <p class="card-text">
                        Lorem ipsum cursus eget nunc fermentum pharetra ullamcorper lorem sed turpis in sed pretium
                        magna risus metus sit malesuada adipiscing ac at aliquam eget condimentum risus pulvinar platea
                        ac magna laoreet.
                    </p>
                </div>
                <div class="card">
                    <div class="icon-wrapper">
                        <img src="resource/about-3.svg" alt="Students icon" class="icon">
                    </div>
                    <h3 class="card-title">+5000 Student</h3>
                    <p class="card-text">
                        Lorem ipsum cursus eget nunc fermentum pharetra ullamcorper lorem sed turpis in sed pretium
                        magna risus metus sit malesuada adipiscing ac at aliquam eget condimentum risus pulvinar platea
                        ac magna laoreet.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- end about -->
    <!-- start collage -->
    <section class="collage py-5">
        <div class="container">
            <h2 class="text-center mb-4 fw-bold">Collage Gallery</h2>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6" style="
                justify-content: space-around;
                display: flex;
                flex-direction: column;">
                    <div class="rounded-2">
                        <img src="resource/collage-1.svg" alt="Image 1">
                    </div>
                    <div class="row py-2">
                        <div class="rounded-2 col-6">
                            <img src="resource/collage-2.svg" alt="Image 2">
                        </div>
                        <div class="rounded-2 col-6">
                            <img src="resource/collage-3.svg" alt="Image 3">
                        </div>
                    </div>

                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="row py-2">
                        <div class="rounded-2 col-6">
                            <img src="resource/collage-4.svg" alt="Image 4">
                        </div>
                        <div class="rounded-2 col-6">
                            <img src="resource/collage-5.svg" alt="Image 5">
                        </div>
                    </div>
                    <div class="rounded-2">
                        <img src="resource/collage-6.svg" alt="Image 6">
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- end collage -->
    
    <!-- start latest news -->
    <?php if (!empty($news)): ?>
    <section class="latest-news py-5">
        <div class="container">
            <h2 class="text-center mb-4 fw-bold">Latest News</h2>
            <div class="row">
                <?php foreach ($news as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($item['image_path'])): ?>
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" class="card-img-top" alt="News Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($item['content']), 0, 150) . '...'; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><?php echo date('F j, Y', strtotime($item['created_at'])); ?></small>
                                <a href="page/news/news-detail.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- end latest news -->
    
    <!-- start contact -->
    <div class="contact-container">
        <h2>Contact us</h2>
        <div class="contact-box">
            <form id="contactForm">
                <div id="contactMessage" class="alert" style="display: none;"></div>
                <input type="text" placeholder="Full Name" id="fullName" name="fullName" required />
                <input type="text" placeholder="Subject" id="subject" name="subject" required />
                <textarea placeholder="Type what you want" id="message" name="message" required></textarea>
                <button type="submit">Send Message</button>
                <input type="hidden" name="contact_submit" value="1">
            </form>
            <div class="contact-image">
                <img src="resource/undraw_contract_upwc 1.png" alt="Contact Illustration" />
            </div>
        </div>
    </div>
    <!-- end contact -->
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
                    <li>
                        <a1 href="#">News</a1>
                    </li>
                    <li>
                        <a1 href="#">Site map</a1>
                    </li>
                    <li>
                        <a1 href="#">About us</a1>
                    </li>
                    <li>
                        <a1 href="#">Join us</a1>
                    </li>
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
    <script src="script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Contact form AJAX submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('contactMessage');
            
            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.textContent = data.message;
                messageDiv.className = data.status === 'success' ? 'alert alert-success' : 'alert alert-danger';
                messageDiv.style.display = 'block';
                
                if (data.status === 'success') {
                    document.getElementById('contactForm').reset();
                    setTimeout(() => {
                        messageDiv.style.display = 'none';
                    }, 5000);
                }
            })
            .catch(error => {
                messageDiv.textContent = 'An error occurred. Please try again later.';
                messageDiv.className = 'alert alert-danger';
                messageDiv.style.display = 'block';
                console.error('Error:', error);
            });
        });
    </script>
</body>

</html> 