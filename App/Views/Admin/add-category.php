<?php
// Sayfa: add-category.php
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AkademikX | Admin Panel</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="/akademikx/public/assets/css/admin_nav.css" rel="stylesheet" />
  <link rel="stylesheet" href="/akademikx/public/assets/css/btn.css" />

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
  <?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

  <?php if (isset($_GET['succsess'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
      Ders başarıyla eklendi.
    </div>
  <?php endif; ?>

  <div class="page mt-4">
    <form action="/akademikx/public/api/admin/add-category" method="POST">
      <div class="form-title text-center">Kategori Oluştur</div>

      <div class="mb-3">
        <label for="title" class="form-label">Kategori Başlığı</label>
        <input type="text" class="form-control" id="title" name="title" required>
      </div>

      <button type="submit" class="btn btn-primary">Kaydet</button>
    </form>
  </div>

  <?php include __DIR__ . '/../partials/nav_js.php'; ?>

  <script>
    setTimeout(function () {
      let alert = document.getElementById("successAlert");
      if (alert) {
        alert.classList.remove("show");
        alert.classList.add("fade");
        setTimeout(() => alert.remove(), 500);
      }
    }, 3000);
  </script>
</body>
</html>
