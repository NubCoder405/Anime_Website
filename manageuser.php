<?php
include("db_connection.php"); // Include database connection
session_start();

// Restrict access to admin users only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Fetch user data
$sql = "SELECT * FROM users"; 
$result = mysqli_query($conn, $sql);

// Handle adding user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Insert new user into the database
    $query = "INSERT INTO users (username, email, role) VALUES ('$name', '$email', '$role')";
    if (mysqli_query($conn, $query)) {
        header("Location: manageuser.php");
        exit();
    }
}

// Handle editing user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
    $userId = $_POST['user_id'];
    $name = $_POST['edit_name'];
    $email = $_POST['edit_email'];
    $role = $_POST['edit_role'];

    $query = "UPDATE users SET username='$name', email='$email', role='$role' WHERE id='$userId'";
    if (mysqli_query($conn, $query)) {
        header("Location: manageuser.php");
        exit();
    }
}

// Handle deleting user
if (isset($_GET['delete_id'])) {
    $userId = $_GET['delete_id'];
    $query = "DELETE FROM users WHERE id='$userId'";
    if (mysqli_query($conn, $query)) {
        header("Location: manageuser.php");
        exit();
    }
}

// Handle banning user
if (isset($_GET['ban_id'])) {
    $userId = $_GET['ban_id'];
    $query = "UPDATE users SET banned=1 WHERE id='$userId'";
    if (mysqli_query($conn, $query)) {
        header("Location: manageuser.php");
        exit();
    }
}

// Handle unbanning user
if (isset($_GET['unban_id'])) {
    $userId = $_GET['unban_id'];
    $query = "UPDATE users SET banned=0 WHERE id='$userId'";
    if (mysqli_query($conn, $query)) {
        header("Location: manageuser.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Users</title>
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
        <h2 class="mb-4">Manage Users</h2>
        <!-- Add User Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
        <!-- User Table -->
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($user = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td>
                  <button class="btn btn-warning btn-sm edit" data-bs-toggle="modal" data-bs-target="#editUserModal" data-id="<?php echo $user['id']; ?>" data-name="<?php echo $user['username']; ?>" data-email="<?php echo $user['email']; ?>" data-role="<?php echo $user['role']; ?>">Edit</button>
                  <a href="?delete_id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm delete">Delete</a>
                  <?php if ($user['banned']): ?>
                    <a href="?unban_id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm unban">Unban</a>
                  <?php else: ?>
                    <a href="?ban_id=<?php echo $user['id']; ?>" class="btn btn-secondary btn-sm ban">Ban</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Add User Modal -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST">
            <div class="mb-3">
              <label for="user-name" class="form-label">Name</label>
              <input type="text" class="form-control" name="name" id="user-name" required />
            </div>
            <div class="mb-3">
              <label for="user-email" class="form-label">Email</label>
              <input type="email" class="form-control" name="email" id="user-email" required />
            </div>
            <div class="mb-3">
              <label for="user-role" class="form-label">Role</label>
              <input type="text" class="form-control" name="role" id="user-role" required />
            </div>
            <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit User Modal -->
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST">
            <input type="hidden" id="edit-user-id" name="user_id" />
            <div class="mb-3">
              <label for="edit-user-name" class="form-label">Name</label>
              <input type="text" class="form-control" id="edit-user-name" name="edit_name" required />
            </div>
            <div class="mb-3">
              <label for="edit-user-email" class="form-label">Email</label>
              <input type="email" class="form-control" id="edit-user-email" name="edit_email" required />
            </div>
            <div class="mb-3">
              <label for="edit-user-role" class="form-label">Role</label>
              <input type="text" class="form-control" id="edit-user-role" name="edit_role" required />
            </div>
            <button type="submit" name="edit_user" class="btn btn-success">Update User</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#editUserModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var name = button.data('name');
        var email = button.data('email');
        var role = button.data('role');

        $('#edit-user-id').val(id);
        $('#edit-user-name').val(name);
        $('#edit-user-email').val(email);
        $('#edit-user-role').val(role);
      });

      // Toggle Sidebar
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
