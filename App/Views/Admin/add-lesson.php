<?php
// Sayfa: add-lesson.php
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
      padding: 20px;
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
    <form action="/akademikx/public/api/admin/add-lesson" method="POST">
      <div class="form-title text-center">Ders Oluştur</div>

      <div class="mb-3">
        <label for="title" class="form-label">Ders Başlığı</label>
        <input type="text" class="form-control" id="title" name="title" required>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Açıklama</label>
        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="category" class="form-label">Kategori</label>
          <select class="form-select" id="category" name="category" required>
            <option value="">Kategori Seçiniz</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="teacher" class="form-label">Öğretmen</label>
          <select class="form-select" id="teacher" name="teacher" required>
            <option value="">Öğretmen Seçiniz</option>
          </select>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Kaydet</button>
    </form>
  </div>

  <?php include __DIR__ . '/../partials/nav_js.php'; ?>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const teacherSelect = document.getElementById('teacher');
    const categorySelect = document.getElementById('category');

    async function getTeachersApi() {
      try {
        const response = await fetch('/akademikx/public/api/admin/get-teachers');
        if (!response.ok) throw new Error('Öğretmenler API hatası: ' + response.status);
        const result = await response.json();
        const teacherData = Array.isArray(result) ? result : result.data;
        let teachers = [];
        if (Array.isArray(teacherData)) {
          teachers = teacherData;
        } else if (typeof teacherData === 'object' && teacherData.id) {
          teachers = [teacherData];
        } else {
          console.error("Beklenmeyen öğretmen verisi:", result);
          return;
        }
        teacherSelect.innerHTML = '<option value="">Öğretmen Seçiniz</option>';
        teachers.forEach(teacher => {
          const option = document.createElement('option');
          option.value = teacher.id;
          option.textContent = teacher.username;
          teacherSelect.appendChild(option);
        });
      } catch (error) {
        console.error("Öğretmen API Hatası:", error);
      }
    }

    async function getCategoriesApi() {
      try {
        const response = await fetch('/akademikx/public/api/admin/get-categories');
        if (!response.ok) throw new Error('Kategori API hatası: ' + response.status);
        const result = await response.json();
        const categoryData = Array.isArray(result) ? result : result.data;
        if (Array.isArray(categoryData)) {
          categorySelect.innerHTML = '<option value="">Kategori Seçiniz</option>';
          categoryData.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
          });
        } else {
          console.error("Beklenmeyen kategori verisi:", result);
        }
      } catch (error) {
        console.error("Kategori API Hatası:", error);
      }
    }

    getTeachersApi();
    getCategoriesApi();
  });

  setTimeout(() => {
    const alert = document.getElementById("successAlert");
    if (alert) {
      alert.classList.remove("show");
      alert.classList.add("fade");
      setTimeout(() => alert.remove(), 500);
    }
  }, 3000);
  </script>
</body>
</html>
