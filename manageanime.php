<?php
include("db_connection.php"); // Include database connection
session_start();

// Restrict access to admin users only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Fetch anime data
$sql = "SELECT * FROM anime_details"; 
$result = mysqli_query($conn, $sql);

// Handle adding anime
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_anime'])) {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $description = $_POST['description']; // New description field
    $image = $_FILES['image']['name'];
    $video = $_FILES['video']['name'];

    // File upload handling
    $imageTmpName = $_FILES['image']['tmp_name'];
    $videoTmpName = $_FILES['video']['tmp_name'];
    $imagePath = "assets/Image/$image";
    $videoPath = "assets/video/$video";
    move_uploaded_file($imageTmpName, $imagePath);
    move_uploaded_file($videoTmpName, $videoPath);

    // Insert new anime into the database
    $query = "INSERT INTO anime_details (name, genre, description, image_url, video_url) VALUES ('$title', '$genre', '$description', '$image', '$video')";
    if (mysqli_query($conn, $query)) {
        header("Location: manageanime.php");
        exit();
    }
}

// Handle editing anime
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_anime'])) {
    $animeId = $_POST['anime_id'];
    $title = $_POST['edit_title'];
    $genre = $_POST['edit_genre'];
    $description = $_POST['edit_description']; // New description field

    // Initialize query
    $query = "UPDATE anime_details SET name='$title', genre='$genre', description='$description'";

    // Handle image upload if a new image is provided
    if (!empty($_FILES['edit_image']['name'])) {
        $image = $_FILES['edit_image']['name'];
        $imageTmpName = $_FILES['edit_image']['tmp_name'];
        $imagePath = "assets/Image/$image";
        move_uploaded_file($imageTmpName, $imagePath);
        $query .= ", image_url='$image'";
    }

    // Handle video upload if a new video is provided
    if (!empty($_FILES['edit_video']['name'])) {
        $video = $_FILES['edit_video']['name'];
        $videoTmpName = $_FILES['edit_video']['tmp_name'];
        $videoPath = "assets/video/$video";
        move_uploaded_file($videoTmpName, $videoPath);
        $query .= ", video_url='$video'";
    }

    // Complete the query
    $query .= " WHERE id='$animeId'";

    if (mysqli_query($conn, $query)) {
        header("Location: manageanime.php");
        exit();
    }
}

// Handle deleting anime
if (isset($_GET['delete_id'])) {
    $animeId = $_GET['delete_id'];
    $query = "DELETE FROM anime_details WHERE id='$animeId'";
    if (mysqli_query($conn, $query)) {
        header("Location: manageanime.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Anime</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background-color: rgb(200, 240, 209);
    }
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
    .table-responsive {
      overflow-x: auto;
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
        <h2 class="mb-4">Manage Anime</h2>
        <!-- Add Anime Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addAnimeModal">Add Anime</button>
        <!-- Anime Table -->
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Genre</th>
                <th>Description</th>
                <th>Image</th>
                <th>Video</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($anime = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?php echo $anime['id']; ?></td>
                  <td><?php echo $anime['name']; ?></td>
                  <td><?php echo $anime['genre']; ?></td>
                  <td><?php echo $anime['description']; ?></td>
                  <td><?php echo $anime['image_url']; ?></td>
                  <td><?php echo $anime['video_url']; ?></td>
                  <td>
                    <button class="btn btn-warning btn-sm edit" data-bs-toggle="modal" data-bs-target="#editAnimeModal" data-id="<?php echo $anime['id']; ?>" data-title="<?php echo $anime['name']; ?>" data-genre="<?php echo $anime['genre']; ?>" data-description="<?php echo $anime['description']; ?>">Edit</button>
                    <a href="?delete_id=<?php echo $anime['id']; ?>" class="btn btn-danger btn-sm delete">Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Anime Modal -->
  <div class="modal fade" id="addAnimeModal" tabindex="-1" aria-labelledby="addAnimeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAnimeModalLabel">Add New Anime</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="anime-title" class="form-label">Title</label>
              <input type="text" class="form-control" name="title" id="anime-title" required />
            </div>
            <div class="mb-3">
              <label for="anime-genre" class="form-label">Genre</label>
              <input type="text" class="form-control" name="genre" id="anime-genre" required />
            </div>
            <div class="mb-3">
              <label for="anime-description" class="form-label">Description</label>
              <textarea class="form-control" name="description" id="anime-description" required></textarea>
            </div>
            <div class="mb-3">
              <label for="anime-image" class="form-label">Image</label>
              <input type="file" class="form-control" name="image" id="anime-image" accept="image/*" required />
            </div>
            <div class="mb-3">
              <label for="anime-video" class="form-label">Video</label>
              <input type="file" class="form-control" name="video" id="anime-video" accept="video/*" required />
            </div>
            <button type="submit" name="add_anime" class="btn btn-primary">Add Anime</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Anime Modal -->
  <div class="modal fade" id="editAnimeModal" tabindex="-1" aria-labelledby="editAnimeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAnimeModalLabel">Edit Anime</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" enctype="multipart/form-data">
            <input type="hidden" id="edit-anime-id" name="anime_id" />
            <div class="mb-3">
              <label for="edit-anime-title" class="form-label">Title</label>
              <input type="text" class="form-control" id="edit-anime-title" name="edit_title" required />
            </div>
            <div class="mb-3">
              <label for="edit-anime-genre" class="form-label">Genre</label>
              <input type="text" class="form-control" id="edit-anime-genre" name="edit_genre" required />
            </div>
            <div class="mb-3">
              <label for="edit-anime-description" class="form-label">Description</label>
              <textarea class="form-control" id="edit-anime-description" name="edit_description" required></textarea>
            </div>
            <div class="mb-3">
              <label for="edit-anime-image" class="form-label">Image</label>
              <input type="file" class="form-control" name="edit_image" id="edit-anime-image" accept="image/*" />
            </div>
            <div class="mb-3">
              <label for="edit-anime-video" class="form-label">Video</label>
              <input type="file" class="form-control" name="edit_video" id="edit-anime-video" accept="video/*" />
            </div>
            <button type="submit" name="edit_anime" class="btn btn-success">Update Anime</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#editAnimeModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var title = button.data('title');
        var genre = button.data('genre');
        var description = button.data('description');

        $('#edit-anime-id').val(id);
        $('#edit-anime-title').val(title);
        $('#edit-anime-genre').val(genre);
        $('#edit-anime-description').val(description);
      });

      // Toggle Sidebar
      $("#menu-toggle").click(function(){
        $("#wrapper").toggleClass("toggled");
      });
    });
  </script>
</body>
</html>
