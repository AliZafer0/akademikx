<?php
    session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);


    require_once('../prepared-features/php/general/db.php');
    require_once('../prepared-features/php/general/functions.php');
  
    if(isset($_SESSION['user_id']) && isset($_GET['id']))
    {
      if(courseTeacher($pdo,$_GET['id'],$_SESSION['user_id']) == false)
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

     
    $lesson_id = $_GET['id'];
    $teacher_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM courses WHERE id = $lesson_id";
    $stmt_lesson = $pdo->prepare($sql);
    $stmt_lesson->execute();
    $lessons = $stmt_lesson->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM course_schedule WHERE course_id = $lesson_id";
    $stmt_course_schedule = $pdo->prepare($sql);
    $stmt_course_schedule->execute();
    $course_schedule = $stmt_course_schedule->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM course_contents WHERE type = 'video' and course_id = '$lesson_id'";
    $stmt_videos = $pdo->prepare($sql);
    $stmt_videos->execute();
    $videos = $stmt_videos->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM course_contents WHERE type = 'image' and course_id = '$lesson_id'";
    $stmt_images = $pdo->prepare($sql);
    $stmt_images->execute();
    $images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM course_contents WHERE type = 'document' and course_id = $lesson_id";
    $stmt_documents = $pdo->prepare($sql);
    $stmt_documents->execute();
    $documents = $stmt_documents->fetchAll(PDO::FETCH_ASSOC);
     
    if(isset($_POST['day_of_week']) && isset($_POST['start_time']) && isset($_POST['end_time']))
    {
       $day_of_week = $_POST['day_of_week'];
       $start_time = $_POST['start_time'];
       $end_time = $_POST['end_time'];
       $course_id = $lesson_id;

       $sql = "INSERT INTO course_schedule (course_id,day_of_week,start_time,end_time) VALUES (?,?,?,?)";
       $stmt_add_time = $pdo->prepare($sql);
       $stmt_add_time->execute([$course_id,$day_of_week,$start_time,$end_time]);

    }
    
    if (isset($_FILES['uploadVideo_file']) && $_FILES['uploadVideo_file']['error'] === UPLOAD_ERR_OK) {
        
        // Başlık ve açıklama
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';

        // Dosya bilgileri
        $fileTmpPath = $_FILES['uploadVideo_file']['tmp_name'];
        $fileName = $_FILES['uploadVideo_file']['name'];
        $fileSize = $_FILES['uploadVideo_file']['size'];
        $fileType = $_FILES['uploadVideo_file']['type'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Yeni dosya ismi (timestamp + rastgele)
        $newFileName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $fileExtension;

        // Hedef klasör
        $uploadFileDir = '../media/videos/';
        $dest_path = $uploadFileDir . $newFileName;

        // Dosya türü ve boyut kontrolü (isteğe göre artırılabilir)
        $allowedExtensions = ['mp4', 'avi', 'mov', 'webm'];
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            die('❌ Geçersiz dosya türü.');
        }

        if ($fileSize > 500 * 1024 * 1024) { // 500 MB sınırı
            die('❌ Dosya çok büyük.');
        }

        // Dosyayı taşı
        if (move_uploaded_file($fileTmpPath, $dest_path)) {

            $sql = "INSERT INTO course_contents (course_id,teacher_id,type,title,description,file_url) VALUES (?,?,?,?,?,?)";
            $stmt_video_add = $pdo->prepare($sql);
            $stmt_video_add->execute([$lesson_id,$teacher_id,'video',$title,$description,$newFileName]);
        } else {
            echo "❌ Dosya yükleme sırasında hata oluştu.";
        }

    }
    if(isset($_FILES['uploadImages_file']) && $_FILES['uploadImages_file']['error']=== UPLOAD_ERR_OK)
    {
      // Başlık ve açıklama
      $title = $_POST['title'] ?? '';
      $description = $_POST['description'] ?? '';

    // Dosya bilgileri
      $fileTmpPath = $_FILES['uploadImages_file']['tmp_name'];
      $fileName = $_FILES['uploadImages_file']['name'];
      $fileSize = $_FILES['uploadImages_file']['size'];
      $fileType = $_FILES['uploadImages_file']['type'];
      $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

      $newFileName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $fileExtension;

      $uploadFileDir = "../media/images/";
      $dest_path = $uploadFileDir . $newFileName;

      $allowedExtensions= ['png','jpg','webn','svg'];
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            die('❌ Geçersiz dosya türü.');
        }

        if ($fileSize > 500 * 1024 * 1024) { // 500 MB sınırı
            die('❌ Dosya çok büyük.');
        }

        // Dosyayı taşı
        if (move_uploaded_file($fileTmpPath, $dest_path)) {

            $sql = "INSERT INTO course_contents (course_id,teacher_id,type,title,description,file_url) VALUES (?,?,?,?,?,?)";
            $stmt_image_add = $pdo->prepare($sql);
            $stmt_image_add->execute([$lesson_id,$teacher_id,'image',$title,$description,$newFileName]);
        } else {
            echo "❌ Dosya yükleme sırasında hata oluştu.";
        }

    }

    if(isset($_FILES['uploadDocuments_file']) && $_FILES['uploadDocuments_file']['error']=== UPLOAD_ERR_OK)
    {
        // Başlık ve açıklama
      $title = $_POST['title'] ?? '';
      $description = $_POST['description'] ?? '';

    // Dosya bilgileri
      $fileTmpPath = $_FILES['uploadDocuments_file']['tmp_name'];
      $fileName = $_FILES['uploadDocuments_file']['name'];
      $fileSize = $_FILES['uploadDocuments_file']['size'];
      $fileType = $_FILES['uploadDocuments_file']['type'];
      $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

      $newFileName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $fileExtension;

      $uploadFileDir = "../media/documents/";
      $dest_path = $uploadFileDir . $newFileName;

      $allowedExtensions= ['pdf','docx','xlsx'];
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            die('❌ Geçersiz dosya türü.');
        }

        if ($fileSize > 500 * 1024 * 1024) { // 500 MB sınırı
            die('❌ Dosya çok büyük.');
        }

        // Dosyayı taşı
        if (move_uploaded_file($fileTmpPath, $dest_path)) {

            $sql = "INSERT INTO course_contents (course_id,teacher_id,type,title,description,file_url) VALUES (?,?,?,?,?,?)";
            $stmt_documents_add = $pdo->prepare($sql);
            $stmt_documents_add->execute([$lesson_id,$teacher_id,'document',$title,$description,$newFileName]);
        } else {
            echo "❌ Dosya yükleme sırasında hata oluştu.";
        }

    }
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
          <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#scheduleModal">+ Gün & Saat Ekle</button>
        </div>
      </div>
    </div>


    <!-- Videolu İçerikler -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">🎥 Videolu İçerikler</h5>
          <ul class="list-group list-group-flush mb-3">
            <?php foreach($videos as $video): ?>
              <li class="list-group-item"> <a class="stylish-link" href="../lesson/play_lesson_video.php?video_name=<?=htmlspecialchars($video['file_url'])?>"><?=htmlspecialchars($video['title'])?></a></li>
            <?php endforeach;?>
          </ul>
          <button class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#uploadVideoModal">+ Video Ekle</button>
        </div>
      </div>
    </div>

    <!-- Görsel İçerikler -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">🖼️ Görsel İçerikler</h5>
          <ul class="list-group list-group-flush mb-3">
            <?php foreach($images as $image):?>
                <li class="list-group-item"> <a class="stylish-link" href="../lesson/play_lesson_image.php?image_name=<?=htmlspecialchars($image['file_url'])?>"><?=htmlspecialchars($image['title'])?></a></li>
            <?php endforeach;?>
          </ul>
          <button class="btn btn-outline-warning w-100" data-bs-toggle="modal" data-bs-target="#uploadImagesModal">+ Görsel Ekle</button>
        </div>
      </div>
    </div>

    <!-- Belgeler -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">📄 Belgeler</h5>
          <ul class="list-group list-group-flush mb-3">
             <?php foreach($documents as $document): ?>
              <li class="list-group-item"> <a class="stylish-link" href="../lesson/play_lesson_document.php?document_name=<?=htmlspecialchars($document['file_url'])?>"><?=htmlspecialchars($document['title'])?></a></li>
            <?php endforeach;?>
          </ul>
          <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#uploadDocumentsModal">+ Belge Ekle</button>
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
</div>
    <?php include_once('../prepared-features/php/general/upload_documents_modal.php');?>
    <?php include_once('../prepared-features/php/general/upload_images_modal.php');?>
    <?php include_once('../prepared-features/php/general/upload_video_modal.php');?>
    <?php include_once('../prepared-features/php/general/time_add_modal.php');?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>