<?php 
session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

// Database connection and comment handling
$servername  = "localhost";
$db_username = "root";
$db_password = "";
$dbname      = "anime_website";
$conn        = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Database connection failed. Please try again later.");
}
$anime_id = isset($_GET['anime_id']) ? (int)$_GET['anime_id'] : 1; // Get anime_id from URL or default to 1
$episode_id = isset($_GET['episode_id']) ? (int)$_GET['episode_id'] : 1; // Get episode_id from URL or default to 1

// Fetch anime details and video URL
$anime_query = "SELECT name, video_url FROM anime_details WHERE id = ?";
$anime_stmt = $conn->prepare($anime_query);
$anime_stmt->bind_param("i", $anime_id);
$anime_stmt->execute();
$anime_result = $anime_stmt->get_result();
$anime = $anime_result->fetch_assoc();
$anime_title = $anime ? $anime['name'] : "Anime Title";
$video_url = $anime ? 'assets/Video/' . $anime['video_url'] : 'assets/Video/default.mp4';

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text']) && isset($_SESSION['user_id'])) {
    $comment_text = $_POST['comment_text'];
    $user_id      = $_SESSION['user_id'];
    $episode_id   = (int)$_POST['episode_id']; // Ensure episode_id is taken from the form
    $parent_id    = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : NULL; // Handle parent_id for replies
    $stmt = $conn->prepare("INSERT INTO comments (anime_id, episode_id, user_id, comment_text, parent_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiisi", $anime_id, $episode_id, $user_id, $comment_text, $parent_id);
    $stmt->execute();
    $stmt->close();
    header("Location: Watch.php?anime_id=$anime_id&episode_id=$episode_id");
    exit;
}

// Fetch stored comments for the current episode
$query = "SELECT comments.id, comments.comment_text, users.username, comments.created_at, users.profile_picture, 
          (SELECT COUNT(*) FROM comment_likes WHERE comment_id = comments.id AND type = 'like') AS likes,
          (SELECT COUNT(*) FROM comment_likes WHERE comment_id = comments.id AND type = 'dislike') AS dislikes
          FROM comments 
          JOIN users ON comments.user_id = users.id 
          WHERE comments.anime_id = ? AND comments.episode_id = ? AND comments.parent_id IS NULL 
          ORDER BY comments.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $anime_id, $episode_id);
$stmt->execute();
$comments_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($anime_title); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/watch.css">
  <style>
    .profile-pic {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
    }
    .container {
        margin-top: 80px; /* Push the whole page down */
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
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="btn btn-success">Login</a>
                <a href="signup.php" class="btn btn-success">Sign Up</a>
            <?php else: ?>
                <a href="profile.php" class="btn btn-primary">
                    <div class="profile-circle">
                        <?php 
                            $username = $_SESSION['username'];
                            echo strtoupper(substr($username, 0, 1));
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

    <div class="container py-3">
        <h1 class="text-center mb-3"><?php echo htmlspecialchars($anime_title); ?></h1>
        <h2 class="episode-title" id="episode-title">Select an Episode</h2>

        <!-- Video Section -->
        <div class="video-container mb-3">
            <video id="video-player" controls style="width: 100%; height: auto;">
                <source src="<?php echo $video_url; ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <!-- Episode Boxes -->
        <div class="d-flex flex-wrap justify-content-center mb-5">
            <div class="episode-box" onclick="playEpisode('Episode 1: The Beginning', '<?php echo $video_url; ?>', 1)">Ep 1</div>
            <div class="episode-box" onclick="playEpisode('Episode 2: A New Journey', '<?php echo $video_url; ?>', 2)">Ep 2</div>
            <div class="episode-box" onclick="playEpisode('Episode 3: The Challenge', '<?php echo $video_url; ?>', 3)">Ep 3</div>
            <div class="episode-box" onclick="playEpisode('Episode 4: Hidden Powers', '<?php echo $video_url; ?>', 4)">Ep 4</div>
            <div class="episode-box" onclick="playEpisode('Episode 5: Climax', '<?php echo $video_url; ?>', 5)">Ep 5</div>
        </div>

        <!-- Comments Section -->
        <div class="mb-5">
            <h2>Comments</h2>
            <?php if (isset($_SESSION['user_id'])): ?>
                <form id="commentForm" action="Watch.php?anime_id=<?php echo $anime_id; ?>&episode_id=<?php echo $episode_id; ?>" method="POST">
                    <!-- Hidden field to hold the episode number -->
                    <input type="hidden" id="episode_id" name="episode_id" value="<?php echo $episode_id; ?>">
                    <textarea name="comment_text" class="form-control mt-3 mb-3" rows="3" placeholder="Leave a comment..." required></textarea>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            <?php else: ?>
                <p>You must <a href="login.php">log in</a> to comment.</p>
            <?php endif; ?>
            <div class="comments-section">
                <?php while ($row = $comments_result->fetch_assoc()): ?>
                    <div class="comment" id="comment-<?php echo $row['id']; ?>">
                        <div class="comment-meta">
                            <img src="<?php echo (!empty($row['profile_picture'])) ? 'uploads/' . htmlspecialchars($row['profile_picture']) : 'assets/Image/default.jpg'; ?>" alt="Profile" class="profile-pic">
                            <span class="comment-username"><strong><?php echo htmlspecialchars($row['username']); ?></strong></span>
                            <span class="comment-date" style="font-size:0.8em;"><?php echo htmlspecialchars($row['created_at']); ?></span>
                        </div>
                        <p class="comment-text"><?php echo htmlspecialchars($row['comment_text']); ?></p>
                        <div class="comment-actions">
                            <button class="like-btn btn btn-sm btn-light" data-id="<?php echo $row['id']; ?>">
                                👍 <span class="like-count"><?php echo $row['likes']; ?></span>
                            </button>
                            <button class="dislike-btn btn btn-sm btn-light" data-id="<?php echo $row['id']; ?>">
                                👎 <span class="dislike-count"><?php echo $row['dislikes']; ?></span>
                            </button>
                            <button class="reply-btn btn btn-sm btn-light" data-id="<?php echo $row['id']; ?>">Reply</button>
                        </div>

                        <!-- Hidden Reply Form -->
                        <form class="reply-form" action="Watch.php?anime_id=<?php echo $anime_id; ?>&episode_id=<?php echo $episode_id; ?>" method="POST" style="display: none;">
                            <input type="hidden" name="anime_id" value="<?php echo $anime_id; ?>">
                            <input type="hidden" name="episode_id" value="<?php echo $episode_id; ?>">
                            <input type="hidden" name="parent_id" value="<?php echo $row['id']; ?>">
                            <textarea name="comment_text" class="form-control mt-2 mb-2" rows="2" placeholder="Reply..." required></textarea>
                            <button type="submit" class="btn btn-secondary btn-sm">Submit Reply</button>
                        </form>

                        <!-- Display replies below the comment if they exist -->
                        <?php
                        // Fetch replies for the current comment
                        $reply_query = "SELECT comments.id, comments.comment_text, users.username, comments.created_at, users.profile_picture 
                                        FROM comments 
                                        JOIN users ON comments.user_id = users.id 
                                        WHERE comments.parent_id = ? 
                                        ORDER BY comments.created_at DESC";
                        $reply_stmt = $conn->prepare($reply_query);
                        $reply_stmt->bind_param("i", $row['id']);
                        $reply_stmt->execute();
                        $reply_result = $reply_stmt->get_result();

                        if ($reply_result->num_rows > 0) {
                            while ($reply = $reply_result->fetch_assoc()) {
                                ?>
                                <div class="reply">
                                    <div class="comment-meta">
                                        <img src="<?php echo (!empty($reply['profile_picture'])) ? 'uploads/' . htmlspecialchars($reply['profile_picture']) : 'assets/Image/default.jpg'; ?>" alt="Profile" class="profile-pic">
                                        <span class="comment-username"><strong><?php echo htmlspecialchars($reply['username']); ?></strong></span>
                                        <span class="comment-date" style="font-size:0.8em;"><?php echo htmlspecialchars($reply['created_at']); ?></span>
                                    </div>
                                    <p class="comment-text"><?php echo htmlspecialchars($reply['comment_text']); ?></p>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer text-light py-4" style="background-color: rgb(11, 12, 42);">
        <div class="container">
            <div class="text-center mt-3">
                <a href="#top" class="btn btn-success rounded-circle go-to-top">
                    <i class="bi bi-arrow-up text-light"></i> ^
                </a>
            </div>
            <div class="row align-items-center">
                <div class="col-md-4 text-md-start text-center mb-3 mb-md-0">
                    <a href="index.php" class="text-light text-decoration-none fs-4">
                        <img src="assets/Image/Logo.png" alt="Logo" class="footer-logo">
                    </a>
                </div>
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <a href="index.php" class="text-light text-decoration-none mx-2">Homepage</a>
                    <a href="blog.html" class="text-light text-decoration-none mx-2">Our Blog</a>
                    <a href="contact.html" class="text-light text-decoration-none mx-2">Contacts</a>
                </div>
                <div class="col-md-4 text-md-end text-center">
                    <p class="mb-0 text-white">&copy; <script>document.write(new Date().getFullYear());</script> All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/comments.js"></script> <!-- Ensure this script is included -->
    <script src="assets/js/watch.js"></script>
    <script>
        // Override the playEpisode function to update the episode id used in comments
        function playEpisode(title, url, episodeId) {
            // Extract the episode number from title (expects format like "Episode 1: The Beginning")
            var match = title.match(/Episode\s+(\d+)/i);
            if (match && match[1]) {
                document.getElementById('episode_id').value = episodeId;
                document.getElementById('episode-title').textContent = title; // Update the episode title
                // Update the URL to reflect the selected episode
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('episode_id', episodeId);
                window.history.replaceState({}, '', `${window.location.pathname}?${urlParams}`);
                // Fetch and display comments for the selected episode
                fetchComments(episodeId);
            }
            // Existing code to play the video
            var video = document.getElementById('video-player');
            video.src = url;
            video.play();
        }

        // Automatically play the first episode when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            playEpisode('Episode 1: The Beginning', '<?php echo $video_url; ?>', 1);
        });

        // Fetch and display comments for the selected episode
        function fetchComments(episodeId) {
            fetch(`Watch.php?anime_id=<?php echo $anime_id; ?>&episode_id=${episodeId}`)
                .then(response => response.text())
                .then(data => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const commentsSection = doc.querySelector('.comments-section');
                    document.querySelector('.comments-section').innerHTML = commentsSection.innerHTML;
                    attachEventListeners(); // Re-attach event listeners after updating comments
                });
        }

        // Like/dislike functionality
        function attachEventListeners() {
            document.querySelectorAll('.like-btn, .dislike-btn').forEach(button => {
                button.addEventListener('click', function() {
                    var commentId = this.getAttribute('data-id');
                    var action = this.classList.contains('like-btn') ? 'like' : 'dislike';
                    fetch('like_dislike.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ comment_id: commentId, action: action })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.closest('.comment-actions').querySelector('.like-count').textContent = data.likes;
                            this.closest('.comment-actions').querySelector('.dislike-count').textContent = data.dislikes;
                        }
                    });
                });
            });

            // Toggle reply form visibility
            document.querySelectorAll('.reply-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const replyForm = this.closest('.comment').querySelector('.reply-form');
                    replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
                });
            });
        }

        // Attach event listeners on initial load
        attachEventListeners();

        function handleSearch(event) {
            if (event.key === 'Enter') {
                const query = event.target.value;
                window.location.href = `search.php?query=${query}`;
            }
        }
    </script>
    <?php $conn->close(); ?>
</body>
</html>