<?php
    session_start();
    require_once('../prepared-features/php/general/db.php');
    require_once('../prepared-features/php/general/functions.php');    

    if(!isset($_GET['lesson_id']))
    {
        header('location: ../');
        exit;
    }

    $lesson_id = $_GET['lesson_id'];

    $sql = "SELECT * FROM courses WHERE id = '$lesson_id'";
    $stmt_course = $pdo->prepare($sql);
    $stmt_course->execute();
    $lessons = $stmt_course->fetch(PDO::FETCH_ASSOC);
    
    $sql = "SELECT COUNT(*) as count FROM course_contents WHERE course_id = '$lesson_id'";
    $stmt_course_content = $pdo->prepare($sql);
    $stmt_course_content->execute();
    $lesson_contents = $stmt_course_content->fetch(PDO::FETCH_ASSOC);

    if(isset($_POST['lesson_id']) && isset($_POST['user_id']))
    {
        $lesson_id_post = $_POST['lesson_id'];
        $user_id = $_SESSION['user_id'];

        $sql = "INSERT INTO enrollments (student_id,course_id) VALUES (?,?)";
        $stmt_user = $pdo->prepare($sql);
        $stmt_user->execute([$user_id,$lesson_id_post]);
    }
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
         .course-banner {
            max-height: 400px;
            object-fit: cover;
            border-radius: 1rem;
        }
        .register-btn {
            transition: all 0.3s ease-in-out;
        }
        .register-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <?php include_once('../prepared-features/php/general/navbar.php') ?>

<div class="container py-5">
  <div class="row g-4">
    
    <!-- Sol kısım: görsel -->
    <div class="col-md-6">
      <img src="../media/images/attachments/general-lesson-image.jpg" alt="Ders Görseli" class="img-fluid course-banner shadow-sm">
    </div>
    
    <!-- Sağ kısım: ders bilgisi -->
    <div class="col-md-6">
      <h1 class="display-6"><?=htmlspecialchars($lessons['title'])?></h1>
      <hr>
      <h5>Ders Açıklaması</h5>
      <p>
        <?=htmlspecialchars($lessons['description'])?>  
      </p>

      <ul class="list-group mb-4">
        <li class="list-group-item"><strong>İçerik Sayısı:</strong> <?=htmlspecialchars($lesson_contents['count'])?> Aktif İçerik</li>
      </ul>

      <?php if(isUserLoggedIn()):?>
        <?php if(!studentlesson($pdo,$_SESSION['user_id'],$lesson_id)):?>
            <form method="POST">
                <input type="hidden" name="lesson_id" value="<?=htmlspecialchars($lesson_id)?>">
                <input type="hidden" name="user_id" value="<?=htmlspecialchars($_SESSION['user_id'])?>">
                <button type="submit" class="btn btn-primary btn-lg w-100">📘 Kayıt Ol</button>
            </form>
        <?php else:?>
            <div class="alert alert-success d-flex align-items-center p-4 rounded-3 shadow-sm" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0zM7.97 11.03a.75.75 0 0 1-1.06 0L4.47 8.59a.75.75 0 1 1 1.06-1.06l1.94 1.94 4.47-4.47a.75.75 0 0 1 1.06 1.06L7.97 11.03z"/>
            </svg>
            <div class="fw-bold fs-5">
                Kayıt başarıyla tamamlandı!
            </div>
            </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>