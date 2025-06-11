<?php
// Sayfa: add-teacher.php
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

  <div class="page mt-4">
    <form action="/akademikx/public/api/admin/add-teacher" method="POST">
      <div class="form-title text-center">Öğretmen Ekle</div>

      <div class="row mb-3">
        <div class="col-md-5">
          <label for="username" class="form-label">Kullanıcı Adı</label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>

        <div class="col-md-5">
          <label for="password" class="form-label">Şifre</label>
          <div class="input-group">
            <input type="password" class="form-control" id="password" name="password" required>
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">👁️</button>
          </div>
        </div>

        <div class="col-md-2 d-flex align-items-end">
          <button type="button" class="btn header-mor-btn w-100" onclick="generatePassword()">Oluştur</button>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <label for="status" class="form-label">Onay Durumu</label>
          <select class="form-select text-center" id="status" name="approv" required>
            <option value="1">Onaylı</option>
            <option value="0">Onaysız</option>
          </select>
        </div>
      </div>

      <button type="submit" class="btn header-yesil-btn btn-save">Kaydet</button>
    </form>
  </div>

  <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header">
          <h5 class="modal-title">✔ Öğretmen Eklendi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
        </div>
        <div class="modal-body">
          <p><strong>Kullanıcı Adı:</strong> <span id="teacherUsername"><?= htmlspecialchars($createdTeacher['username']) ?></span></p>
          <p><strong>Şifre:</strong> <span id="teacherPassword"><?= htmlspecialchars($createdTeacher['password']) ?></span></p>
          <button class="btn btn-sm btn-outline-primary mt-2" onclick="copyCredentials()">Bilgileri Kopyala</button>
          <p class="mt-2" id="copyStatus" style="font-size: 0.9em; color: green;"></p>
        </div>
      </div>
    </div>
  </div>

  <script>
    function generatePassword(length = 10) {
      const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
      let password = "";
      for (let i = 0; i < length; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
      }
      document.getElementById("password").value = password;
    }
  </script>
  <?php include __DIR__ . '/../partials/nav_js.php'; ?>

  <script>
    document.getElementById("togglePassword").addEventListener("click", function () {
      const passwordInput = document.getElementById("password");
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      this.textContent = type === "password" ? "👁️" : "🙈";
    });
  </script>

  <?php if (isset($_GET['showModal'])): ?>
  <script>
    window.addEventListener("DOMContentLoaded", function () {
      var successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    });

    function copyCredentials() {
      const username = document.getElementById("teacherUsername").innerText;
      const password = document.getElementById("teacherPassword").innerText;
      const textToCopy = `Kullanıcı Adı: ${username}\nŞifre: ${password}`;

      navigator.clipboard.writeText(textToCopy).then(() => {
        document.getElementById("copyStatus").innerText = "Bilgiler panoya kopyalandı!";
      }).catch(() => {
        document.getElementById("copyStatus").innerText = "Kopyalama başarısız oldu.";
      });
    }
  </script>
  <?php endif; ?>
</body>
</html>
