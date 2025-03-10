<?php
session_start();
$conn = new mysqli("localhost", "root", "", "anime_website");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format! Please enter a valid email.";
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    }

    // If there's an error, store the message for later
    if ($error_message !== "") {
        // Just store the error message and let the form reload
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();

        if ($check_email->num_rows > 0) {
            $error_message = "Email already registered! <a href='login.html'>Login here</a>";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $success_message = "Registration successful!";
                echo "<script>alert('$success_message'); window.location.href = 'login.html';</script>";
                exit(); // Stop further script execution
            } else {
                $error_message = "Error: " . $stmt->error;
            }

            // Close connections
            $stmt->close();
        }

        // Close email check
        $check_email->close();
    }
    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/signup.css">
</head>

<body>
    <nav>
        <a href="index.html">Home</a>
        <a href="Blog.html">Blog</a>
        <a href="Aboutus.html">About Us</a>
        <a href="Contact.html">Contact Us</a>
        <input type="text" placeholder="Search...">
    </nav>
    <div class="nav2">
        <div class="logo">
            <a href="index.html">
                <img src="assets/Image/Logo.png" alt="Logo">
            </a>
        </div>
    </div>
    <section class="login-section mb-5">
        <h1>Sign Up</h1>
        <p>Welcome to the official Anime blog.</p>
        <div class="row">
            <div class="col-md-6 login-form">
                <h3>Sign Up</h3>
                <form method="POST" action="">
                    <div class="input-group">
                        <input type="text" name="username" placeholder="Username" required>
                    </div>
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </form>
                <?php
                if ($error_message !== "") {
                    echo "<script>alert('$error_message');</script>";
                }
                ?>
            </div>
            <div class="col-md-6 register-section d-flex flex-column justify-content-center">
                <h3>Already have an account?</h3>
                <a href="login.html" class="btn btn-outline-primary text-white mt-3">Login</a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
