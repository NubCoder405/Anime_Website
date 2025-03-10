<?php
session_start(); // Start the session
// Assume you have the database connection set up
include('db_connection.php');

if (isset($_GET['query'])) {
    $search_query = '%' . $_GET['query'] . '%'; // Add wildcards for partial matching

    // Query the database to get the search results based on the query
    $sql = "SELECT * FROM anime_details WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Search Results</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="assets/css/index.css" />
  <style>
    body {
      margin-top: 150px; /* Adjust the value as needed */
    }
    nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    nav a, nav form {
      margin-left: 10px;
    }
    .card-body {
      color: white; /* Set card text color to white */
    }
    .card-img-top {
      height: 200px; /* Set a fixed height for the images */
      object-fit: cover; /* Maintain aspect ratio and cover the area */
    }
  </style>
</head>
<body>
  <section>
    <nav>
      <div>
        <a href="index.php">Home</a>
        <a href="Aboutus.php">About Us</a>
        <a href="Contact.php">Contact Us</a>
      </div>
      <form action="search.php" method="GET" class="d-flex">
        <input type="text" name="query" placeholder="Search..." class="form-control" required onkeypress="handleSearch(event)">
        <!-- No need for a submit button, form will submit on pressing enter -->
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
                // Use the first letter of the username as an example
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

    <div class="container mt-5">
      <h2>Search Results:</h2>

      <!-- Display search results -->
      <div class="row mt-4">
        <?php
        if (isset($result) && $result->num_rows > 0) {
            while ($anime = $result->fetch_assoc()) {
                // Display each anime as a card
                echo "
                <div class='col-md-4'>
                  <div class='card mb-4'>
                    <img src='assets/Image/{$anime['image_url']}' class='card-img-top' alt='{$anime['name']}'>
                    <div class='card-body'>
                      <h5 class='card-title'>{$anime['name']}</h5>
                      <a href='Details.php?id={$anime['id']}' class='btn btn-primary'>Watch</a>
                    </div>
                  </div>
                </div>
                ";
            }
        } else {
            echo "<p>No results found for your search.</p>";
        }
        ?>
      </div>
    </div>
  </section>
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
