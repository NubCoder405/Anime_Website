<?php
session_start();

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "anime_website";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get anime ID from URL
$anime_id = isset($_GET['id']) ? $_GET['id'] : 1; // Default to 1 if no ID is provided

// Handle comment submission (for top-level/general comments)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text']) && !isset($_POST['parent_id']) && isset($_SESSION['user_id'])) {
    $comment_text = $_POST['comment_text'];
    $user_id = $_SESSION['user_id'];
    $episode_id = 0; // General comments

    $stmt = $conn->prepare("INSERT INTO comments (anime_id, episode_id, user_id, comment_text, parent_id, created_at) VALUES (?, ?, ?, ?, NULL, NOW())");
    $stmt->bind_param("iiis", $anime_id, $episode_id, $user_id, $comment_text);

    if ($stmt->execute()) {
        $new_comment_id = $conn->insert_id;
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            // Fetch new comment and output snippet for AJAX requests
            $sql = "SELECT comments.id, comments.comment_text, users.username, users.profile_picture, comments.created_at 
                    FROM comments 
                    JOIN users ON comments.user_id = users.id 
                    WHERE comments.id = ?";
            $stmt2 = $conn->prepare($sql);
            $stmt2->bind_param("i", $new_comment_id);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            if ($comment = $result2->fetch_assoc()) {
                ?>
                <div class="comment" id="comment-<?php echo $comment['id']; ?>">
                    <div class="comment-meta">
                        <img src="<?php echo (isset($comment['profile_picture']) && !empty($comment['profile_picture'])) ? 'uploads/' . htmlspecialchars($comment['profile_picture']) : 'assets/Image/default.jpg'; ?>" 
                             alt="Profile" class="profile-pic">
                        <span class="comment-username"><strong><?php echo htmlspecialchars($comment['username']); ?></strong></span>
                        <span class="comment-date" style="font-size:0.8em;"><?php echo htmlspecialchars($comment['created_at']); ?></span>
                    </div>
                    <p class="comment-text"><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                </div>
                <?php
            }
            exit;
        } else {
            header("Location: details.php?id=" . $anime_id . "#comments-section");
            exit;
        }
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch anime details from the database
$sql = "SELECT * FROM anime_details WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $anime_id);
$stmt->execute();
$result = $stmt->get_result();
$anime = $result->fetch_assoc();

if (!$anime) {
    echo "Anime not found!";
    exit;
}

// Fetch top-level comments (where parent_id IS NULL) with likes/dislikes
$comment_sql = "SELECT comments.id, comments.comment_text, users.username, users.profile_picture, 
                comments.created_at, 
                (SELECT COUNT(*) FROM comment_likes WHERE comment_id = comments.id AND type = 'like') AS likes,
                (SELECT COUNT(*) FROM comment_likes WHERE comment_id = comments.id AND type = 'dislike') AS dislikes
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE comments.anime_id = ? AND comments.episode_id = 0 AND comments.parent_id IS NULL 
                ORDER BY comments.created_at DESC";


$comment_stmt = $conn->prepare($comment_sql);
$comment_stmt->bind_param("i", $anime_id);
$comment_stmt->execute();
$comment_result = $comment_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime Details - <?php echo htmlspecialchars($anime['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/details.css">
    <style>
        .profile-pic {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
        .comment {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .reply-form {
            margin-top: 10px;
        }
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
            <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="login.php" class="btn btn-success">Login</a>
                <a href="signup.html" class="btn btn-success">Sign Up</a>
            <?php } else { ?>
                <a href="profile.php">
                    <div class="profile-circle">
                        <?php 
                            $username = $_SESSION['username'];
                            echo strtoupper(substr($username, 0, 1));
                        ?>
                    </div>
                </a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            <?php } ?>
        </div>
    </nav>
    <div class="nav2">
        <div class="logo">
            <a href="index.php">
                <img src="assets/Image/Logo.png" alt="Logo">
            </a>
        </div>
    </div>

    <!-- Anime Details Section -->
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <img src="assets/Image/<?php echo htmlspecialchars($anime['image_url']); ?>" alt="Anime Cover" class="img-fluid rounded">
            </div>
            <div class="col-md-8">
                <h1 class="mb-3"><?php echo htmlspecialchars($anime['name']); ?></h1>
                <p><strong>Synopsis:</strong> <?php echo nl2br(htmlspecialchars($anime['description'])); ?></p>
                <a href="Watch.php?anime_id=<?php echo $anime_id; ?>" class="btn btn-success">Watch Now</a>
            </div>
        </div>
    </div>

<!-- Comments Section -->
<div id="comments-section" class="container py-5">
    <h2>Comments</h2>

    <?php if (isset($_SESSION['user_id'])) { ?>
        <!-- Updated form with id -->
        <form id="commentForm" action="details.php?id=<?php echo $anime_id; ?>" method="POST">
            <input type="hidden" name="anime_id" value="<?php echo $anime_id; ?>">
            <input type="hidden" name="episode_id" value="0">
            <textarea name="comment_text" class="form-control mt-3 mb-3" rows="3" placeholder="Leave a comment..." required></textarea>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    <?php } else { ?>
        <p>You must <a href="login.php">log in</a> to comment.</p>
    <?php } ?>

    <div class="comments-section">
        <?php while ($row = $comment_result->fetch_assoc()) { ?>
            <div class="comment" id="comment-<?php echo $row['id']; ?>">
                <div class="comment-meta">
                    <img src="<?php echo (isset($row['profile_picture']) && !empty($row['profile_picture'])) ? 'uploads/' . htmlspecialchars($row['profile_picture']) : 'assets/Image/default.jpg'; ?>" 
                         alt="Profile" class="profile-pic">
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
                <form class="reply-form" action="details.php?id=<?php echo $anime_id; ?>" method="POST" style="display: none;">
                    <input type="hidden" name="anime_id" value="<?php echo $anime_id; ?>">
                    <input type="hidden" name="episode_id" value="0">
                    <input type="hidden" name="parent_id" value="<?php echo $row['id']; ?>">
                    <textarea name="comment_text" class="form-control mt-2 mb-2" rows="2" placeholder="Reply..." required></textarea>
                    <button type="submit" class="btn btn-secondary btn-sm">Submit Reply</button>
                </form>

                <!-- Display replies below the comment if they exist -->
                <?php
                // Fetch replies for the current comment
                $reply_sql = "SELECT comments.id, comments.comment_text, users.username, users.profile_picture, 
                                    comments.created_at 
                              FROM comments 
                              JOIN users ON comments.user_id = users.id 
                              WHERE comments.parent_id = ? 
                              ORDER BY comments.created_at DESC";
                $reply_stmt = $conn->prepare($reply_sql);
                $reply_stmt->bind_param("i", $row['id']);
                $reply_stmt->execute();
                $reply_result = $reply_stmt->get_result(); // Added missing get_result() method

                while ($reply_row = $reply_result->fetch_assoc()) { // Added while loop to fetch replies
                    ?>
                    <div class="comment reply" id="comment-<?php echo $reply_row['id']; ?>">
                        <div class="comment-meta">
                            <img src="<?php echo (isset($reply_row['profile_picture']) && !empty($reply_row['profile_picture'])) ? 'uploads/' . htmlspecialchars($reply_row['profile_picture']) : 'assets/Image/default.jpg'; ?>" 
                                 alt="Profile" class="profile-pic">
                            <span class="comment-username"><strong><?php echo htmlspecialchars($reply_row['username']); ?></strong></span>
                            <span class="comment-date" style="font-size:0.8em;"><?php echo htmlspecialchars($reply_row['created_at']); ?></span>
                        </div>
                        <p class="comment-text"><?php echo htmlspecialchars($reply_row['comment_text']); ?></p>
                    </div>
                    <?php
                }
                $reply_stmt->close(); // Close the statement
                ?>
            </div>
        <?php } ?>
    </div>
</div>

    <!-- Related Content Section -->
    <div class="container py-5">
        <h2>Related Content</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <a href="#" class="text-decoration-none">
                    <div class="card related-card">
                        <img src="assets/Image/tv-1.jpg" alt="Related Anime 1">
                        <div class="card-body">
                            <h5 class="card-title">Related Anime 1</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" class="text-decoration-none">
                    <div class="card related-card">
                        <img src="assets/Image/tv-1.jpg" alt="Related Anime 2">
                        <div class="card-body">
                            <h5 class="card-title">Related Anime 2</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" class="text-decoration-none">
                    <div class="card related-card">
                        <img src="assets/Image/tv-1.jpg" alt="Related Anime 3">
                        <div class="card-body">
                            <h5 class="card-title">Related Anime 3</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer text-light py-4" style="background-color: #0b0c2a;">
        <div class="container">
            <div class="text-center mt-3">
                <a href="#top" class="btn btn-success rounded-circle go-to-top">^</a>
            </div>
            <div class="row align-items-center">
                <div class="col-md-4 text-md-start text-center mb-3 mb-md-0">
                    <a href="index.html" class="text-light text-decoration-none fs-4">
                        <img src="assets/Image/Logo.png" alt="Logo" class="footer-logo">
                    </a>
                </div>
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <a href="index.html" class="text-light text-decoration-none mx-2">Homepage</a>
                    <a href="blog.html" class="text-light text-decoration-none mx-2">Our Blog</a>
                    <a href="contact.html" class="text-light text-decoration-none mx-2">Contacts</a>
                </div>
                <div class="col-md-4 text-md-end text-center">
                    <p class="mb-0 text-white">&copy; <script>document.write(new Date().getFullYear());</script> All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="assets/js/comments.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // AJAX submission for the main comment form
        $("#commentForm").submit(function(e){
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr("action"),
                type: "POST",
                data: form.serialize(),
                success: function(response) {
                    $(".comments-section").prepend(response);
                    form.find("textarea[name='comment_text']").val("");
                }
            });
        });

        // Existing reply form AJAX handling remains unchanged.
        $(".reply-form").on("submit", function(e){
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr("action"),
                type: "POST",
                data: form.serialize(),
                context: form,
                success: function(response) {
                    var parent_id = form.find("input[name='parent_id']").val();
                    $("#comment-" + parent_id).append(response);
                    form.find("textarea[name='comment_text']").val("");
                    form.hide();
                }
            });
        });

        // Like/dislike functionality remains unchanged.
        $(".like-btn, .dislike-btn").click(function() {
            var button = $(this);
            var commentId = button.data("id");
            var action = button.hasClass("like-btn") ? "like" : "dislike";
            $.ajax({
                url: "like_dislike.php",
                type: "POST",
                data: { comment_id: commentId, action: action },
                success: function(response) {
                    var data = JSON.parse(response);
                    button.closest(".comment-actions").find(".like-count").text(data.likes);
                    button.closest(".comment-actions").find(".dislike-count").text(data.dislikes);
                }
            });
        });
        
        $(".reply-btn").click(function() {
            $(this).closest(".comment").find(".reply-form").toggle();
        });
    });

    function handleSearch(event) {
        if (event.key === 'Enter') {
            const query = event.target.value;
            window.location.href = `search.php?query=${query}`;
        }
    }
</script>


</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
