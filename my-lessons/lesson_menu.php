<?php
    session_start();
    require_once("../prepared-features/php/general/functions.php");
    require_once("../prepared-features/php/general/db.php");

    if(!isset($_GET['lesson_id']) || !isset($_SESSION['user_id']) || studentlessonfromid($pdo,$_SESSION['user_id'],$_GET['lesson_id']) == false)
    {
        header('location: ../');
        exit;
    }


    $lesson_id = $_GET['lesson_id'];

    $sql = "SELECT * FROM courses WHERE id = '$lesson_id'";
    $stmt_lesson = $pdo->prepare($sql);
    $stmt_lesson->execute();
    $lessons = $stmt_lesson->fetch(PDO::FETCH_ASSOC);


    $sql = "SELECT * FROM course_contents WHERE course_id = '$lesson_id'";
    $stmt_lesson = $pdo->prepare($sql);
    $stmt_lesson->execute();
    $lesson_contents = $stmt_lesson->fetchAll(PDO::FETCH_ASSOC);

    
    $sql = "SELECT * FROM course_schedule WHERE course_id = '$lesson_id'";
    $stmt_lesson = $pdo->prepare($sql);
    $stmt_lesson->execute();
    $course_schedule = $stmt_lesson->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | <?=htmlspecialchars($lessons['title'])?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    .card-title {
        font-weight: 600;
        font-size: 1.25rem;
    }
    .stylish-link {
    display: block;
    padding: 0.75rem 1rem;
    color: #212529;
    text-decoration: none;
    transition: background-color 0.3s ease, color 0.3s ease;
    }

    .stylish-link:hover {
    background-color: #f0f0f0;
    color: #0d6efd; /* Bootstrap’in birincil mavi tonu */
    }

    .stylish-link:active {
    background-color: #e2e6ea;
    }

    </style>
</head>
<body>
    <?php include_once('../prepared-features/php/general/navbar.php') ?>
<div class="container my-5">
  <h2 class="mb-4 text-center"><?= htmlspecialchars($lessons['title'])?> Dersi Yönetim</h2>

  <div class="row g-4">

    <!-- Ders Günleri ve Saatleri -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">📅 Ders Günleri & Saatleri</h5>
          <ul class="list-group list-group-flush mb-3">
            <?php foreach($course_schedule as $time):?>
                <li class="list-group-item">
                    <?= htmlspecialchars(translateDay($time['day_of_week'])) ?>  
                    <?= date('H:i', strtotime($time['start_time'])) ?> - <?= date('H:i', strtotime($time['end_time'])) ?>
                </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>


    <!-- Videolu İçerikler -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">🎥 Videolu İçerikler</h5>
          <ul class="list-group list-group-flush mb-3">
            <?php foreach($lesson_contents as $content): ?>
                <?php if($content['type'] == 'video'):?>
                    <li class="list-group-item"> <a class="stylish-link" href="../lesson/play_lesson_video.php?video_name=<?=htmlspecialchars($content['file_url'])?>"><?=htmlspecialchars($content['title'])?></a></li>
                <?php endif;?>
            <?php endforeach;?>
          </ul>
        </div>
      </div>
    </div>

    <!-- Görsel İçerikler -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">🖼️ Görsel İçerikler</h5>
          <ul class="list-group list-group-flush mb-3">
            <?php foreach($lesson_contents as $content): ?>
                <?php if($content['type'] == 'image'):?>
                    <li class="list-group-item"> <a class="stylish-link" href="../lesson/play_lesson_image.php?image_name=<?=htmlspecialchars($content['file_url'])?>"><?=htmlspecialchars($image['title'])?></a></li>
                <?php endif;?>
            <?php endforeach;?>
          </ul>
        </div>
      </div>
    </div>

    <!-- Belgeler -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">📄 Belgeler</h5>
          <ul class="list-group list-group-flush mb-3">
              <?php foreach($lesson_contents as $content): ?>
                <?php if($content['type'] == 'document'):?>
                    <li class="list-group-item"> <a class="stylish-link" href="../lesson/play_lesson_document.php?document_name=<?=htmlspecialchars($content['file_url'])?>"><?=htmlspecialchars($content['title'])?></a></li>
                <?php endif;?>
            <?php endforeach;?>
          </ul>
        </div>
      </div>
    </div>

    <!-- Testler / Sorular -->
    <div class="col-md-12">
      <div class="card shadow-sm h-100 border-info">
        <div class="card-body">
          <h5 class="card-title">📝 Testler ve Sorular</h5>
          <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item">Quiz 1: Basit Cümleler</li>
            <li class="list-group-item">Test 2: Anlam Bilgisi</li>
          </ul>
          <div class="d-grid">
            <button class="btn btn-info text-white" onclick="window.location.href='question_add/add.php?lesson_id=<?=htmlspecialchars($lesson_id)?>'" >+ Yeni Soru / Test Ekle</button>
          </div>
        </div>
      </div>
    </div>

  </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>