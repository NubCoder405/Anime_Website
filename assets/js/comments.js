document.addEventListener('DOMContentLoaded', function() {
    // Like/Dislike buttons functionality
    document.querySelectorAll('.like-btn, .dislike-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.getAttribute('data-id');
            const action = this.classList.contains('like-btn') ? 'like' : 'dislike';

            console.log(`Clicked ${action} on comment ${commentId}`); // Debugging

            fetch('like_dislike.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `comment_id=${encodeURIComponent(commentId)}&action=${encodeURIComponent(action)}`
            })
            .then(response => response.json())
            .then(data => {
                console.log("AJAX Response:", data); // Debugging
                if (data.likes !== undefined && data.dislikes !== undefined) {
                    document.querySelector(`.like-btn[data-id="${commentId}"]`).innerHTML = `👍 ${data.likes}`;
                    document.querySelector(`.dislike-btn[data-id="${commentId}"]`).innerHTML = `👎 ${data.dislikes}`;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Show reply form when clicking "Reply"
    document.querySelectorAll(".reply-btn").forEach(button => {
        button.addEventListener("click", function() {
            this.nextElementSibling.style.display = "block";
        });
    });
});

$(document).ready(function() {
    // Toggle the visibility of the reply form when the reply button is clicked
    $(".reply-btn").click(function() {
        var commentId = $(this).data("id");
        $("#comment-" + commentId).find(".reply-form").toggle();
    });

    $(".like-btn, .dislike-btn").click(function() {
        var button = $(this);
        var commentId = button.data("id");
        var action = button.hasClass("like-btn") ? "like" : "dislike";

        $.ajax({
            url: "like_dislike.php", // Ensure it's pointing to the correct file
            type: "POST",
            data: { comment_id: commentId, action: action },
            success: function(response) {
                var data = JSON.parse(response);

                // Update the like/dislike count
                button.closest(".comment-actions").find(".like-count").text(data.likes);
                button.closest(".comment-actions").find(".dislike-count").text(data.dislikes);
            }
        });
    });
});