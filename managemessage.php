<?php
include("db_connection.php"); // Include database connection
session_start();

// Restrict access to admin users only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch contact messages data
$sql = "SELECT * FROM contact_messages"; 
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Messages</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color: rgb(200, 240, 209);
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
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-primary" id="menu-toggle">Menu</button>
      </nav>
      <div class="container mt-5">
        <h2 class="mb-4">Manage Messages</h2>
        <!-- Messages Table -->
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>User ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Message</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($message = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?php echo $message['id']; ?></td>
                <td><?php echo $message['user_id']; ?></td>
                <td><?php echo $message['name']; ?></td>
                <td><?php echo $message['email']; ?></td>
                <td><?php echo $message['message']; ?></td>
                <td><?php echo $message['created_at']; ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    // Toggle Sidebar
    $("#menu-toggle").click(function(){
      $("#wrapper").toggleClass("toggled");
    });
  </script>

  <style>
    #wrapper {
      display: flex;
    }
    #sidebar-wrapper {
      min-width: 250px;
      max-width: 250px;
      background-color: #343a40;
      color: #fff;
    }
    #page-content-wrapper {
      flex-grow: 1;
    }
    .toggled #sidebar-wrapper {
      display: none;
    }
    .navbar-nav {
      margin-left: auto;
    }
  </style>
</body>
</html>
