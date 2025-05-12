<?php
    session_start();
    require_once("../prepared-features/php/general/functions.php");
    require_once("../prepared-features/php/general/db.php");

    $sql = "SELECT c.id, c.title, c.description
            FROM enrollments e
            JOIN courses c ON e.course_id = c.id
            WHERE e.student_id = ?";

    $stmt_sql = $pdo->prepare($sql);
    $stmt_sql->execute([$_SESSION['user_id']]);
    $lessons_user = $stmt_sql->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Anasayfa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .course-card {
            border-radius: 1rem;
            transition: transform 0.3s ease;
        }
        .course-card:hover {
            transform: scale(1.02);
        }
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        .progress-bar {
            transition: width 0.6s ease;
        }
    </style>
</head>
<body>
    <?php include_once('../prepared-features/php/general/navbar.php') ?>
        <div class="container py-5">
  <h2 class="mb-4 text-center">📚 Kayıtlı Dersleriniz</h2>
  
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php foreach($lessons_user as $lessons):?>
    <!-- KART 1 -->
    <div class="col">
      <div class="card course-card shadow-sm">
        <img src="../media/images/attachments/general-lesson-image.jpg" class="card-img-top" alt="Python Dersi">
        <div class="card-body">
          <h5 class="card-title"><?=htmlspecialchars($lessons['title'])?></h5>
          <p class="card-text text-muted"><?=htmlspecialchars($lessons['description'])?></p>
          <div class="mb-2">
            <small class="text-muted">İlerleme: %65</small>
            <div class="progress">
              <div class="progress-bar bg-success" role="progressbar" style="width: 65%;"></div>
            </div>
          </div>
          <a href="lesson_menu.php?lesson_id=<?=htmlspecialchars($lessons['id'])?>" class="btn btn-outline-primary w-100 mt-2">Derse Git</a>
        </div>
      </div>
    </div>
    <?php endforeach;?>


  </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>