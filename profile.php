<?php
session_start();
require 'db_connection.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, profile_picture, password, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile updates (name, email, profile picture)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['email'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Current password

    // Validate the current password
    if (password_verify($password, $user['password'])) {
        // Update the profile information (username and email)
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $email, $user_id);
        $stmt->execute();

        // Profile Picture Upload Handling
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $target_dir = "assets/Profile_picture/";
            $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if it's a valid image
            if (getimagesize($_FILES["profile_picture"]["tmp_name"])) {
                // Move the file to the target directory
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                    // Update profile_picture in the database
                    $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                    $stmt->bind_param("si", basename($_FILES["profile_picture"]["name"]), $user_id);
                    $stmt->execute();
                } else {
                    echo "<script>alert('Error uploading the file.');</script>";
                }
            } else {
                echo "<script>alert('File is not an image.');</script>";
            }
        }

        // Redirect back to profile page after updates
        header("Location: profile.php");
        exit;
    } else {
        echo "<script>alert('Incorrect current password.');</script>";
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the current password
    if (password_verify($current_password, $user['password'])) {
        // Check if new password and confirm password match
        if ($new_password === $confirm_password) {
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_new_password, $user_id);
            $stmt->execute();

            // Redirect back to profile page after password change
            header("Location: profile.php");
            exit;
        } else {
            echo "<script>alert('New password and confirm password do not match.');</script>";
        }
    } else {
        echo "<script>alert('Incorrect current password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(200, 240, 209); /* Lighter green background */
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-links {
            display: flex;
            gap: 15px;
        }
        .search-bar {
            flex-grow: 1;
            margin: 0 15px;
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="Aboutus.php">About Us</a>
            <a href="Contact.php">Contact Us</a>
        </div>
        <input type="text" class="search-bar" placeholder="Search..." onkeypress="handleSearch(event)">
        <div class="nav-links">
            <a href="logout.php" style="background-color: red;">Logout</a>
        </div>
    </nav>
    <div class="nav2">
        <div class="logo">
            <a href="index.php">
                <img src="assets/Image/Logo.png" alt="Logo">
            </a>
        </div>
    </div>

    <!-- Profile Section -->
    <div class="container mt-5">
        <div class="card mx-auto p-4 text-center" style="max-width: 500px; background-color: #0b0c2a; color: white;">
            <div class="position-relative">
                <img src="<?= $user['profile_picture'] ? 'assets/Profile_picture/' . $user['profile_picture'] : 'assets/Image/default.jpg' ?>" alt="Profile Picture"
                     class="rounded-circle border border-light" style="width: 120px; height: 120px;">
            </div>
            <h3 class="mt-3"><?= htmlspecialchars($user['username']) ?></h3>
            <p><?= htmlspecialchars($user['email']) ?></p>

            <!-- Edit Profile Button -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button>
            <?php if (strcasecmp($user['role'], 'admin') === 0): ?>
                <button class="btn btn-danger mt-3" onclick="window.location.href='dashboard.php'">Go to Dashboard</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="profile.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="<?= $user_id ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">New Name</label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">New Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Change Profile Picture</label>
                            <input type="file" name="profile_picture" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Current Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="profile.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="<?= $user_id ?>">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleSearch(event) {
            if (event.key === 'Enter') {
                const query = event.target.value;
                window.location.href = `search.php?query=${query}`;
            }
        }
    </script>
</body>
</html>
