<?php 
session_start(); 
include 'db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO contact_messages (user_id, name, email, message, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $name, $email, $message, $created_at);

        if ($stmt->execute()) {
            echo "<script>alert('Message sent successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('You must be logged in to send a message.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/Contact.css">
</head>
<body>
    <nav style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center;">
            <a href="index.php">Home</a>
            <a href="Aboutus.php">About Us</a>
            <a href="Contact.php">Contact Us</a>
            <form action="search.php" method="GET" class="d-flex" style="display: inline;">
                <input type="text" name="query" placeholder="Search..." class="form-control" required style="display: inline; width: auto;">
            </form>
        </div>
        <div style="display: flex; align-items: center;">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="btn btn-success">Login</a>
                <a href="signup.html" class="btn btn-success">Sign Up</a>
            <?php else: ?>
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
    <div class="nav2"> <!-- Changed from <nav2> to <div class="nav2"> -->
        <div class="logo">
            <a href="index.php">
                <img src="assets/Image/Logo.png" alt="Logo">
            </a>
        </div>
    </div>
    <header>
        <div class="container">
            <h1>FAQ</h1>
        </div>
    </header>
    <div class="container">
        <div class="contact-form">
            <h2>Contact Us</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="Contact.php" method="post">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                    <button type="submit">Send Message</button>
                </form>
            <?php else: ?>
                <p>You must be logged in to send a message. <a href="login.php">Login here</a>.</p>
            <?php endif; ?>
        </div>
        <div class="faq-section">
            <h2>Frequently Asked Questions</h2>
            <div class="faq">
                <h3>How can I create an account?</h3>
                <p>You can create an account by clicking on the "Sign Up" button on the top right corner and filling out the registration form.</p>
            </div>
            <div class="faq">
                <h3>What are the benefits of a premium membership?</h3>
                <p>Premium members enjoy ad-free streaming, access to better and faster servers, access to exclusive content, and the ability to download episodes for offline viewing.</p>
            </div>
            <div class="faq">
                <h3>Can I refund my premium account?</h3>
                <p>There is a 30-day refund policy. So, you can easily refund your subscription to our premium feature for a full refund. But we only do one refund for one account, so keep that in mind.</p>
            </div>
            <div class="faq">
                <h3>What devices are supported?</h3>
                <p>Our service is available on web browsers, iOS and Android devices, smart TVs, and gaming consoles.</p>
            </div>
            <div class="faq">
                <h3>How do I report a problem with a video?</h3>
                <p>If you encounter any issues with a video, you can report it by clicking on the "Report" button below the video player. Or you can go to our contact us page to report a more detailed problem.</p>
            </div>
            <div class="faq">
                <h3>Can I request new anime titles?</h3>
                <p>Yes, you can request new anime titles by contacting our support team through the "Contact Us" page.</p>
            </div>
            <div class="faq">
                <h3>How do I change my password?</h3>
                <p>You can change your password by going to the "Account Settings" page and selecting "Change Password".</p>
            </div>
            <div class="faq">
                <h3>How do I cancel my subscription?</h3>
                <p>You can cancel your subscription by going to the "Account Settings" page and selecting "Cancel Subscription".</p>
            </div>
            <div class="faq">
                <h3>Is there a mobile app available?</h3>
                <p>Yes, we have a mobile app available for both iOS and Android devices. You can download it from the App Store or Google Play Store.</p>
            </div>
            <div class="faq">
                <h3>What should I do if a video won't load?</h3>
                <p>If a video isn't loading, please check your internet connection. If the issue persists, try refreshing the page or clearing your browser cache. It may also be a server issue due to a lot of traffic. You can contact support for assistance.</p>
            </div>
            <div class="faq">
                <h3>Do you offer subtitles or dubbing?</h3>
                <p>Yes, we provide subtitles for most of our anime titles, and many of them have dubbed versions available. You can choose your preferred language settings in the video player options.</p>
            </div>
            <div class="faq">
                <h3>How can I update my payment information?</h3>
                <p>To update your payment information, go to the "Account Settings" page and select "Payment Methods." From there, you can add or change your payment details.</p>
            </div>
            <div class="faq">
                <h3>Can I watch anime offline?</h3>
                <p>Yes, premium members can download episodes to watch offline using the mobile app. Simply click the download button next to the episode you want to save.</p>
            </div>
            <div class="faq">
                <h3>Are there parental controls available?</h3>
                <p>Yes, you can set parental controls in your account settings to restrict access to content based on ratings or genres.</p>
            </div>
            <div class="faq">
                <h3>How often is new content added?</h3>
                <p>We regularly update our library with new anime titles, including seasonal releases and exclusive shows. Check back often or sign up for our newsletter to stay updated.</p>
            </div>
            <div class="faq">
                <h3>Is it possible to watch in HD?</h3>
                <p>Yes, all videos are available in HD quality. Depending on your internet speed, you can adjust the video quality settings during playback for the best viewing experience.</p>
            </div>
            <div class="faq">
                <h3>Are there any limits on how many devices I can use at once?</h3>
                <p>With a premium subscription, you can stream on up to 4 devices simultaneously. For free accounts, streaming is limited to one device at a time.</p>
            </div>
            <div class="faq">
                <h3>What happens if I forget my password?</h3>
                <p>If you forget your password, click on the "Forgot Password" link on the login page, and we'll send you instructions on how to reset it via email.</p>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.querySelectorAll('.faq h3').forEach(faq => {
            faq.addEventListener('click', () => {
                faq.nextElementSibling.style.display = faq.nextElementSibling.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script>
</body>
</html>