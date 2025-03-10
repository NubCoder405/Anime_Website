<?php
session_start();

// Replace the following with your actual database connection details
$host = 'localhost';     // Your database host
$dbname = 'anime_website'; // Your database name
$username = 'root';      // Your database username
$password = '';          // Your database password

// Create a PDO instance for the database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the required POST variables are set
if (isset($_POST['comment_id']) && isset($_POST['action']) && in_array($_POST['action'], ['like', 'dislike'])) {
    $commentId = (int)$_POST['comment_id'];
    $action = $_POST['action'];
    $userId = $_SESSION['user_id']; // Assuming the user ID is stored in the session

    // Check if the user already voted on this comment
    $checkVoteQuery = "SELECT * FROM comment_likes WHERE comment_id = ? AND user_id = ?";
    $stmt = $pdo->prepare($checkVoteQuery);
    $stmt->execute([$commentId, $userId]);
    $vote = $stmt->fetch();

    // Insert or update the vote
    if ($vote) {
        // User has already voted, update their vote
        $updateVoteQuery = "UPDATE comment_likes SET type = ? WHERE comment_id = ? AND user_id = ?";
        $stmt = $pdo->prepare($updateVoteQuery);
        $stmt->execute([$action, $commentId, $userId]);
    } else {
        // User hasn't voted yet, insert their vote
        $insertVoteQuery = "INSERT INTO comment_likes (comment_id, user_id, type) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($insertVoteQuery);
        $stmt->execute([$commentId, $userId, $action]);
    }

    // Update the like/dislike count in the comments table
    $likeDislikeCountQuery = "
        SELECT 
            SUM(CASE WHEN type = 'like' THEN 1 ELSE 0 END) AS likes,
            SUM(CASE WHEN type = 'dislike' THEN 1 ELSE 0 END) AS dislikes
        FROM comment_likes
        WHERE comment_id = ?
    ";
    $stmt = $pdo->prepare($likeDislikeCountQuery);
    $stmt->execute([$commentId]);
    $counts = $stmt->fetch();

    // Return the updated like/dislike counts
    echo json_encode([
        'likes' => $counts['likes'],
        'dislikes' => $counts['dislikes']
    ]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
