<?php
// Sayfa: login.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
  <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
    <h4 class="text-center mb-4">Giriş Yap</h4>
    <?php if (isset($_GET['error'])): ?>
        <div class="error-message" style="color: red; margin-bottom: 10px;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>
    <form action="login_check.php" method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Kullanıcı Adı</label>
        <input type="text" class="form-control" id="username" name="username" required maxlength="50">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Şifre</label>
        <input type="password" class="form-control" id="password" name="password">
      </div>
      <a href="../register" class="d-block text-center mt-3 text-decoration-none fw-semibold text-primary">
         Hesabın yoksa <span class="text-decoration-underline">kayıt ol</span>
      </a>
      <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>
