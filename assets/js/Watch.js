function playEpisode(title, videoUrl) {
    // Update the episode title
    document.getElementById('episode-title').innerText = title;
    // Update the video source
    var videoPlayer = document.getElementById('video-player');
    videoPlayer.src = videoUrl;
    videoPlayer.load();
    videoPlayer.play();
  }