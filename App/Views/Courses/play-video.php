<?php
// Sayfa: play-video.php
?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Ders Video</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        .video-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }
        .video-box {
            width: 85%;
            max-width: 2200px;
            height: 70vh;
            display: flex;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
        }
        .video-box .video {
            width: 60%;
            height: 100%;
            background-color: #000;
        }
        .video-box .description {
            width: 40%;
            padding: 20px;
            background-color: #f7f7f7;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .description h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
        }
        .description p {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .description .btn {
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .description .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="video-container">
            <div class="video-box">
                <div class="video" id="video-player-container">
                    <video controls width="100%" height="100%" oncontextmenu="return false;">
                        <source src="" type="video/mp4">
                        Tarayıcınız video etiketini desteklemiyor.
                    </video>
                </div>
                <div class="description">
                    <h2 id="video-title"></h2>
                    <p id="video-description"></p>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const parts = window.location.pathname.split('/');
  const filename = parts[parts.length - 1];

  fetch(`/akademikx/public/file-details/${filename}`)
    .then(res => {
      if (!res.ok) throw new Error('Video bilgisi alınamadı');
      return res.json();
    })
    .then(data => {
      const videoData = data[0] || {};

      const source = document.querySelector('video source');
      if (source) {
        source.src = videoData.file_url ? `../../uploads/videos/${videoData.file_url}` : '';
      }
      document.title = `AkademikX | ${videoData.title || ''}`;
      const titleEl = document.getElementById('video-title');
      if (titleEl) titleEl.textContent = videoData.title || '';
      const descEl = document.getElementById('video-description');
      if (descEl) descEl.textContent = videoData.description || '';
      const videoEl = document.querySelector('video');
      if (videoEl) videoEl.load();
    })
    .catch(err => {
      console.error(err);
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
