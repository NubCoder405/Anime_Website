<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "anime_website";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text']) && isset($_SESSION['user_id'])) {
    $anime_id    = $_POST['anime_id'];
    $episode_id  = $_POST['episode_id'];
    $parent_id   = $_POST['parent_id'];
    $comment_text= $_POST['comment_text'];
    $user_id     = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO comments (anime_id, episode_id, user_id, comment_text, parent_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiisi", $anime_id, $episode_id, $user_id, $comment_text, $parent_id);

    if ($stmt->execute()) {
        $reply_id = $conn->insert_id;
        $sql = "SELECT comments.id, comments.comment_text, users.username, users.profile_picture, comments.created_at 
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE comments.id = ?";
        $reply_stmt = $conn->prepare($sql);
        $reply_stmt->bind_param("i", $reply_id);
        $reply_stmt->execute();
        $result = $reply_stmt->get_result();
        if ($reply = $result->fetch_assoc()) {
            // Return only the reply HTML
            ?>
            <div class="reply">
                <div class="comment-meta">
                    <img src="<?php echo (isset($reply['profile_picture']) && !empty($reply['profile_picture'])) ? 'uploads/' . htmlspecialchars($reply['profile_picture']) : 'assets/Image/default.jpg'; ?>" 
                         alt="Profile" class="profile-pic">
                    <span class="comment-username"><strong><?php echo htmlspecialchars($reply['username']); ?></strong></span>
                    <span class="comment-date" style="font-size:0.8em;"><?php echo htmlspecialchars($reply['created_at']); ?></span>
                </div>
                <p class="comment-text"><?php echo htmlspecialchars($reply['comment_text']); ?></p>
            </div>
            <?php
        }
        $reply_stmt->close();
    }
    $stmt->close();
}
$conn->close();
?>
