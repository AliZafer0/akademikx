<?php
    session_start();
    require_once('../prepared-features/php/general/db.php');
    require_once('../prepared-features/php/general/functions.php');

    if(isset($_SESSION['user_id']) && isset($_GET['video_name']))
    {
      if(courseElm($pdo,documentUrlToCourse($pdo,$_GET['video_name']),$_SESSION['user_id']) == false)
      {
        header('location: ../');
        exit;
      }

    }
    else
    {
      header('location: ../');
      exit;
    }
    
    $video_name = $_GET['video_name'];
    $video_url = '../media/videos/'.$video_name;

    $sql = "SELECT * FROM course_contents WHERE file_url = '$video_name'";
    $stmt_video = $pdo->prepare($sql);
    $stmt_video->execute();
    $video = $stmt_video->fetch(PDO::FETCH_ASSOC);

    // Başlangıçta PHP'de değişkeni tanımlıyoruz
    $videoFinished = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['videoFinished'])) {
        // AJAX isteği ile gelen durumu kontrol ediyoruz
        $videoFinished = $_POST['videoFinished'] === 'true' ? true : false;
        echo "Video durumu: " . ($videoFinished ? "Tamamlandı" : "Devam Ediyor");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | <?=htmlspecialchars($video['title'])?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
   .video-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh; /* Tam ekran yüksekliği */
        }

        .video-box {
            width: 85%; /* Video alanı ekranın %85'i kadar genişlik */
            max-width: 2200px; /* En fazla 1200px genişlik */
            height: 70vh; /* Video alanının yüksekliği ekranın %90'ı kadar */
            display: flex;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
        }

        .video-box .video {
            width: 60%; /* Video alanı sol tarafta geniş olacak */
            height: 100%;
            background-color: #000;
        }

        .video-box .description {
            width: 40%; /* Açıklama alanı sağda daha küçük */
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
    <?php include_once('../prepared-features/php/general/navbar.php') ?>
    <div class="container mt-5">
        <div class="container">
            <div class="video-container">
                <div class="video-box">
                    <div class="video">
                        <video controls width="100%" height="100%" oncontextmenu="return false;">
                            <source src="<?=htmlspecialchars($video_url)?>" type="video/mp4" >
                            Tarayıcınız video etiketini desteklemiyor.
                        </video>
                    </div>
                    <div class="description">
                        <h2><?=htmlspecialchars($video['title'])?></h2>
                        <p><?=htmlspecialchars($video['description'])?></p>
                    </div>
                </div>
            </div>
        </div>
</div>
<script>
    var video = document.getElementById("myVideo");
    var videoFinished = false;

    // Eğer video sonlandıysa, video durumu saklanacak
    video.onended = function() {
        videoFinished = true;
        console.log("Video bitti. Durum:", videoFinished);
        // Durumu localStorage'a kaydediyoruz, sayfa yenilendiğinde dahi veriyi alabiliriz
        localStorage.setItem("videoFinished", "true");
    };

    // Sayfa yüklendiğinde, video durumunu kontrol et
    window.onload = function() {
        var storedStatus = localStorage.getItem("videoFinished");
        if (storedStatus === "true") {
            videoFinished = true;
            console.log("Video daha önce tamamlanmıştı.");
        }
    };

    // Sayfadan çıkıldığında durumu PHP'ye gönder
    window.onbeforeunload = function() {
        if (videoFinished) {
            // AJAX ile video durumu gönder
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("videoFinished=" + videoFinished);
        }
    };
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>