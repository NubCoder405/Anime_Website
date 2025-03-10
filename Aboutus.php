<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/about.css">
    <style>
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: nowrap; /* Ensure content stays in one line */
        }
        nav a, nav form, nav div {
            margin-left: 10px;
            white-space: nowrap; /* Prevent line breaks */
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <a href="index.php">Home</a>
        <a href="Aboutus.php">About Us</a>
        <a href="Contact.php">Contact Us</a>
        <form action="search.php" method="GET" class="d-flex">
            <input type="text" name="query" placeholder="Search..." class="form-control" required>
        </form>
        <div>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <!-- If not logged in -->
                <a href="login.php" class="btn btn-success">Login</a>
                <a href="signup.html" class="btn btn-success">Sign Up</a>
            <?php else: ?>
                <!-- If logged in, show profile circle and logout -->
                <a href="profile.php">
                    <div class="profile-circle">
                        <?php 
                            $username = $_SESSION['username']; 
                            $initials = strtoupper(substr($username, 0, 1)); 
                            echo $initials;
                        ?>
                    </div>
                </a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="nav2">
        <div class="logo">
            <a href="index.php">
                <img src="assets/Image/Logo.png" alt="Logo">
            </a>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero-section">
        <h1>About Us</h1>
        <p>Discover who we are, what we do, and why we do it.</p>
    </div>

    <!-- Content Section -->
    <div class="content-section container">
        <div class="row">
            <div class="col-md-6">
                <img src="assets/Image/Aboutus.jpg" alt="Our Mission" class="img-fluid rounded" style="max-width: 500px; height: auto;">
            </div>
            <div class="col-md-6">
                <h2 class="title">Our Mission</h2>
                <p>Here at Nahime, we have tried our best to build a site for anime lovers from around the world.
                    A comfort place where people can enjoy their favorite shows and 
                    interact with like-minded people who share our passion for anime. 
                    For us fans it is more than just entertainment, It is our passion, it is our comfort, 
                    it is our therapy, it is our escape and our home.
                    Join us on this journey as we try to create a space that anime lovers can call home..</p> 
                </p>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="team-section py-5 bg-light">
        <div class="container">
            <h2 class="title">Meet Our Team</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Bishal Sunar</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Rajib Kumal</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Sumit Shrestha</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer text-light py-4" style="background-color: #0b0c2a;">
        <div class="container">
            <!-- Scroll to Top Button -->
            <div class="text-center mt-3">
                <a href="#top" class="btn btn-success rounded-circle go-to-top">
                    <i class="bi bi-arrow-up text-light"></i> ^
                </a>
            </div>
            <div class="row align-items-center">
                <!-- Logo Section -->
                <div class="col-md-4 text-md-start text-center mb-3 mb-md-0">
                    <a href="index.html" class="text-light text-decoration-none fs-4">
                        <img src="assets/Image/Logo.png" alt="Logo" class="footer-logo">
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <a href="index.html" class="text-light text-decoration-none mx-2">Homepage</a>
                    <a href="blog.html" class="text-light text-decoration-none mx-2">Our Blog</a>
                    <a href="contact.html" class="text-light text-decoration-none mx-2">Contacts</a>
                </div>
                
                <!-- Copyright and Credits -->
                <div class="col-md-4 text-md-end text-center">
                    <p class="mb-0 text-white">&copy; <script>document.write(new Date().getFullYear());</script> All rights reserved.</p>
                </div>
            </div>

        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
