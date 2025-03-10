<?php
session_start();
$conn = new mysqli("localhost", "root", "", "anime_website");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = '';  // Default empty error message

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Get user from database
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php"); // Redirect to profile page
            exit();
        } else {
            $error_message = "Incorrect password.";  // Set error message
        }
    } else {
        $error_message = "No user found with this email.";  // Set error message
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <nav>
        <a href="index.php">Home</a>
        <a href="Aboutus.html">About Us</a>
        <a href="Contact.html">Contact Us</a>
    </nav>

    <div class="nav2">
        <div class="logo">
            <a href="index.php">
                <img src="assets/Image/Logo.png" alt="Logo">
            </a>
        </div>
    </div>

    <section class="login-section">
        <h1>Login</h1>
        <p>Welcome to the official Anime blog.</p>

        <!-- Show error message if any -->
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6 login-form">
                <h3>Login</h3>
                <form method="POST" action="login.php">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login Now</button>
                    <div class="mt-3">
                        <a href="#">Forgot Your Password?</a>
                    </div>
                </form>
            </div>
            <div class="col-md-6 register-section d-flex flex-column justify-content-center">
                <h3>Don't Have An Account?</h3>
                <a href="signup.php" class="btn btn-outline-primary text-white mt-3">Register Now</a>
                <a href="index.php" class="btn btn-outline-secondary text-white mt-3">Guest Mode</a> <!-- Added Guest Mode button -->
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
