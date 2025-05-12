<?php
session_start();
require_once("../prepared-features/php/general/db.php");
require_once("../prepared-features/php/general/functions.php");
require_once("../prepared-features/php/admin/auth_check.php");

$sql = "SELECT COUNT(*) AS student_count FROM users WHERE role = 'student'";
$stmt_student = $pdo->query($sql);
$student = $stmt_student->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) AS teacher_count FROM users WHERE role = 'teacher'";
$stmt_teacher = $pdo->query($sql);
$teacher = $stmt_teacher->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) AS all_count FROM users";
$stmt_all = $pdo->query($sql);
$all_count = $stmt_all->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Admin Panel</title>
        <!-- Bootstrap 5.3-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- font awesome  -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="../prepared-features/css/admin/nav_css.css" rel="stylesheet">

  <style>
    .stat-card {
        background: #1e1e2f;
        color: white;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
        color: #00bcd4;
    }

    .stat-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #ffffff;
    }
</style>

</head>
<body>
    <?php include_once "../prepared-features/php/admin/navbar.php"; ?>
    <div class="container my-5">
  <div class="row g-4">
    <div class="col-md-4">
      <div class="stat-card">
        <i class="fas fa-user-graduate stat-icon"></i>
        <div class="stat-title">Öğrenci Sayısı</div>
        <div class="stat-number"><?=htmlspecialchars($student['student_count'])?></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <i class="fas fa-chalkboard-teacher stat-icon"></i>
        <div class="stat-title">Öğretmen Sayısı</div>
        <div class="stat-number"><?=htmlspecialchars($teacher['teacher_count'])?></div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <i class="fas fa-users stat-icon"></i>
        <div class="stat-title">Toplam Kullanıcı</div>
        <div class="stat-number"><?= htmlspecialchars($all_count['all_count'])?></div>
      </div>
    </div>
  </div>
</div>
    <?php include_once "../prepared-features/php/admin/nav_js.php"; ?>
</body>
</html>