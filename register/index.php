<?php
require_once("../prepared-features/php/general/db.php");

if(isset($_SESSION['user_id']))
{
  header('location: ../');
  exit;
}

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Kullanıcı adı zaten var mı?
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $checkStmt->execute([$username]);
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        echo "Bu kullanıcı adı zaten alınmış.";
    } else {
        // Yeni kullanıcıyı ekle
        $insertStmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $insertStmt->execute([$username, $password]);
        echo "Kayıt başarılı.";
         header("Location: ../login");
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Kayit-Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

  <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
    <h4 class="text-center mb-4">Kayıt Ol</h4>
    <form method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Kullanıcı Adı</label>
        <input type="text" class="form-control" id="username" name="username" required maxlength="50">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Şifre</label>
        <input type="password" class="form-control" id="password" name="password" required minlength="6">
      </div>
      <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
    </form>
  </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>