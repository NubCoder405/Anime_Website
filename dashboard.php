<?php
session_start();
require 'db_connection.php';

// Ensure user is logged in and is an Admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user role from the database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['role'] !== 'admin') {
    // If the user is not an admin, redirect them to the home page or any other page
    header("Location: index.php");
    exit;
}

// Fetch Total Users
$stmt = $conn->prepare("SELECT COUNT(*) as total_users FROM users");
$stmt->execute();
$result = $stmt->get_result();
$total_users = $result->fetch_assoc()['total_users'];

// Fetch Total Anime
$stmt = $conn->prepare("SELECT COUNT(*) as total_anime FROM anime_details");
$stmt->execute();
$result = $stmt->get_result();
$total_anime = $result->fetch_assoc()['total_anime'];

// Fetch Total Comments
$stmt = $conn->prepare("SELECT COUNT(*) as total_comments FROM comments");
$stmt->execute();
$result = $stmt->get_result();
$total_comments = $result->fetch_assoc()['total_comments'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="script.js"></script>
    <style>
        body {
            background-color: rgb(200, 240, 209); /* Lighter green background */
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-end" id="sidebar-wrapper">
            <div class="sidebar-heading text-white text-center py-4">Admin Dashboard</div>
            <div class="list-group list-group-flush">
                <a href="dashboard.php" class="list-group-item list-group-item-action text-white bg-dark">Dashboard</a>
                <a href="manageuser.php" class="list-group-item list-group-item-action text-white bg-dark">Manage Users</a>
                <a href="manageanime.php" class="list-group-item list-group-item-action text-white bg-dark">Manage Anime</a>
                <a href="managemessage.php" class="list-group-item list-group-item-action text-white bg-dark">Manage Messages</a>
                <a href="profile.php" class="list-group-item list-group-item-action text-white bg-dark">Return to Profile</a>
            </div>
        </div>
        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100 p-4">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <button class="btn btn-primary" id="menu-toggle">Menu</button>
            </nav>
            <div class="container-fluid">
                <h1 class="mt-4">Admin Dashboard</h1>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text"><?= $total_users ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Anime</h5>
                                <p class="card-text"><?= $total_anime ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Comments</h5>
                                <p class="card-text"><?= $total_comments ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("#menu-toggle").click(function(){
                $("#wrapper").toggleClass("toggled");
            });
        });
    </script>
    
    <style>
        #wrapper {
            display: flex;
        }
        #sidebar-wrapper {
            min-width: 250px;
            max-width: 250px;
        }
        #page-content-wrapper {
            flex-grow: 1;
        }
        .toggled #sidebar-wrapper {
            display: none;
        }
    </style>
</body>
</html>
