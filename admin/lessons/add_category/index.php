<?php
session_start();
require_once("../../../prepared-features/php/general/db.php");
require_once("../../../prepared-features/php/general/functions.php");
require_once("../../../prepared-features/php/admin/auth_check.php");

$status = false;

if(isset($_POST['title']))
{
    $title = $_POST['title'];

    $sql = "INSERT INTO categories (name) VALUES (?)";
    $stmt_add = $pdo->prepare($sql);
    $stmt_add->execute([$title]);
    $status = true; 
};
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AkademikX | Admin Panel</title>

  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../prepared-features/css/admin/nav_css.css">
  <link rel="stylesheet" href="../../../prepared-features/css/general/btn.css">

  <style>
    .form-title {
      font-size: 1.5rem;
      margin-bottom: 1rem;
      font-weight: 500;
    }
    form {
      max-width: 700px;
      margin: 40px auto;
      background: none;
      padding: 20px;
      border: none;
    }
    .form-control {
      border-radius: 6px;
      border: 1px solid #ccc;
      transition: all 0.3s;
    }
    .form-control:focus {
      border-color: #007bff;
      box-shadow: none;
    }
    .btn-save {
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <?php include_once "../../../prepared-features/php/admin/navbar.php"; ?>

    <?php if($status):?>
         <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
            Kategori Başarı İle Eklendi;
        </div>
    <?php endif;?>

  <div class="page mt-4">
<form method="POST">
  <div class="form-title text-center">Kategori Oluştur</div>

  <!-- Kategori Başlığı -->
  <div class="mb-3">
    <label for="title" class="form-label">Kategori Başlığı</label>
    <input type="text" class="form-control" id="title" name="title" required>
  </div>

  <button type="submit" class="btn btn-primary">Kaydet</button>
</form>

  </div>
<script>
  // Sayfa yüklendikten 3 saniye sonra alert'i gizle
  setTimeout(function () {
    let alert = document.getElementById("successAlert");
    if (alert) {
      // fade out efekti için sınıfı kaldırıyoruz
      alert.classList.remove("show");
      alert.classList.add("fade");
      
      // DOM'dan tamamen kaldırmak istersen:
      setTimeout(() => alert.remove(), 500); // 0.5 saniye sonra sil
    }
  }, 3000); // 3 saniye sonra başla
</script>
</body>
</html>
