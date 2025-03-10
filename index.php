<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Website Template</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/index.css">
  <style>
    /* Example CSS for profile circle */
    .profile-circle {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #007bff;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 18px;
      font-weight: bold;
      margin-left: 10px;
    }
    nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    nav a, nav form {
      margin-left: 10px;
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
        <input type="text" name="query" placeholder="Search..." class="form-control" required>
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
    
    <!-- Slider Section -->
    <div class="slider">
      <div class="list">
        <div class="item">
          <img src="assets/Image/Slider1.jpg" alt="Slider1">
          <div class="content">
            <div class="title">Fate / Stay Night: Unlimited Blade Works</div>
            <div class="type">Action</div>
            <div class="description">After 30 days of travel across the world...</div>
            <div class="button">
              <a href="Details.php?id=1"><button>WATCH NOW</button></a>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="assets/Image/Slider2.jpg" alt="Slider2">
          <div class="content">
            <div class="title">Demon Slayer</div>
            <div class="type">Action</div>
            <div class="description">After Michael Jackson kills his family and...</div>
            <div class="button">
              <a href="Details.php?id=3"><button>WATCH NOW</button></a>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="assets/Image/Solo-Leveling-Slider.jpg" alt="Slider3">
          <div class="content">
            <div class="title">Solo Leveling</div>
            <div class="type">Action</div>
            <div class="description">The E-rank hunter, Sung Chin Woo misteriously awakens powers and mogs everyone</div>
            <div class="button">
              <a href="Details.php?id=2"><button>WATCH NOW</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="thumbnail">
      <div class="item">
        <img src="assets/Image/Slider1.jpg" alt="Slider1">
      </div>
      <div class="item">
        <img src="assets/Image/Slider2.jpg" alt="Slider2">
      </div>
      <div class="item">
        <img src="assets/Image/Solo-Leveling-Slider.jpg" alt="Slider3">
      </div>
    </div>
    
    <div class="arrowButtons">
      <button id="prev" class="prev"> &lt; </button>
      <button id="next" class="next"> &gt; </button>
    </div>
    
    <!-- Trending Cards -->
    <div class="container py-5">
      <h1 class="text-center mb-4">Trending Now</h1>
      <div class="row g-4">
        <!-- Card 1 -->
        <div class="col-md-4">
          <a href="Details.php?id=2" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/Solo-Leveling.jpg" class="card-img-top" alt="Anime 1">
              <div class="card-body text-center">
                <h5 class="card-title">Solo Leveling</h5>
              </div>
            </div>
          </a>
        </div>
        <!-- Card 2 -->
        <div class="col-md-4">
          <a href="Details.php?id=4" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/AOT.jpg" class="card-img-top" alt="Anime 2">
              <div class="card-body text-center">
                <h5 class="card-title">Attack On Titan: Final Season Part 2</h5>
              </div>
            </div>
          </a>
        </div>
        <!-- Card 3 -->
        <div class="col-md-4">
          <a href="Details.php" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/trending-2.jpg" class="card-img-top" alt="Anime 3">
              <div class="card-body text-center">
                <h5 class="card-title">Fate/Stay Night: Unlimited Blade Works</h5>
              </div>
            </div>
          </a>
        </div>
        <!-- Card 4 -->
        <div class="col-md-4">
          <a href="Details.php?id=2" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/Zenshu.jpg" class="card-img-top" alt="Anime 4">
              <div class="card-body text-center">
                <h5 class="card-title">Zenshu</h5>
              </div>
            </div>
          </a>
        </div>
        <!-- Card 5 -->
        <div class="col-md-4">
          <a href="Details.php?id=3" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/Sakamoto-Days.jpg" class="card-img-top" alt="Anime 5">
              <div class="card-body text-center">
                <h5 class="card-title">Sakamoto Days</h5>
              </div>
            </div>
          </a>
        </div>
        <!-- Card 6 -->
        <div class="col-md-4">
          <a href="Details.php?id=4" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/Hanako.jpg" class="card-img-top" alt="Anime 6">
              <div class="card-body text-center">
                <h5 class="card-title">Jibaku Shounen Hanako-kun 2</h5>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
    
    <!-- Popular Cards -->
    <div class="container py-5">
      <h1 class="text-center mb-4">Popular</h1>
      <div class="row g-4">
        <!-- Card 1 -->
        <div class="col-md-4">
          <a href="#" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/pupular-1.jpg" class="card-img-top" alt="Anime 1">
              <div class="card-body text-center">
                <h5 class="card-title">Anime Title 1</h5>
              </div>
            </div>
          </a>
        </div>
        <!-- Card 2 -->
        <div class="col-md-4">
          <a href="#" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/pupular-1.jpg" class="card-img-top" alt="Anime 2">
              <div class="card-body text-center">
                <h5 class="card-title">Anime Title 2</h5>
              </div>
            </div>
          </a>
        </div>
        <!-- Card 3 -->
        <div class="col-md-4">
          <a href="#" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/pupular-1.jpg" class="card-img-top" alt="Anime 3">
              <div class="card-body text-center">
                <h5 class="card-title">Anime Title 3</h5>
              </div>
            </div>
          </a>
        </div>
        <!-- Card 4 -->
        <div class="col-md-4">
          <a href="#" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/pupular-1.jpg" class="card-img-top" alt="Anime 4">
              <div class="card-body text-center">
                <h5 class="card-title">Anime Title 4</h5>
              </div>
            </div>
          </a>
        </div>
        <!-- Card 5 -->
        <div class="col-md-4">
          <a href="#" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/pupular-1.jpg" class="card-img-top" alt="Anime 5">
              <div class="card-body text-center">
                <h5 class="card-title">Anime Title 5</h5>
              </div>
            </div>
          </a>
        </div>
        <!-- Card 6 -->
        <div class="col-md-4">
          <a href="#" class="text-decoration-none">
            <div class="card">
              <img src="assets/Image/pupular-1.jpg" class="card-img-top" alt="Anime 6">
              <div class="card-body text-center">
                <h5 class="card-title">Anime Title 6</h5>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
    
  </section>
  
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
          <a href="index.php" class="text-light text-decoration-none fs-4">
            <img src="assets/Image/Logo.png" alt="Logo" class="footer-logo">
          </a>
        </div>
        
        <!-- Navigation Links -->
        <div class="col-md-4 text-center mb-3 mb-md-0">
          <a href="index.php" class="text-light text-decoration-none mx-2">Homepage</a>
          <a href="blog.html" class="text-light text-decoration-none mx-2">Our Blog</a>
          <a href="Contact.html" class="text-light text-decoration-none mx-2">Contacts</a>
        </div>
        
        <!-- Copyright and Credits -->
        <div class="col-md-4 text-md-end text-center">
          <p class="mb-0 text-white">&copy; <script>document.write(new Date().getFullYear());</script> All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>
  
  <script src="assets/js/index.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
