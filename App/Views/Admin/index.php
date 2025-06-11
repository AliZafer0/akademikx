<?php
// Sayfa: user-summary.php
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AkademikX | Admin Panel</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />

  <link href="/akademikx/public/assets/css/admin_nav.css" rel="stylesheet" />

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
  <?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

  <div class="container my-5">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="stat-card">
          <i class="fas fa-user-graduate stat-icon"></i>
          <div class="stat-title">Öğrenci Sayısı</div>
          <div class="stat-number" id="student-count">Yükleniyor...</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card">
          <i class="fas fa-chalkboard-teacher stat-icon"></i>
          <div class="stat-title">Öğretmen Sayısı</div>
          <div class="stat-number" id="teacher-count">Yükleniyor...</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card">
          <i class="fas fa-users stat-icon"></i>
          <div class="stat-title">Toplam Kullanıcı</div>
          <div class="stat-number" id="total-count">Yükleniyor...</div>
        </div>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/../partials/nav_js.php'; ?>

  <script>
  document.addEventListener("DOMContentLoaded", function () {
    fetch("/akademikx/public/api/admin/statistics")
      .then(res => res.json())
      .then(data => {
        document.getElementById("teacher-count").textContent = data.students.teacher_count;
        document.getElementById("student-count").textContent = data.teachers.student_count;
        document.getElementById("total-count").textContent   = data.total.total_count;
      })
      .catch(err => {
        console.error("Veri alınamadı", err);
      });
  });
  </script>
</body>
</html>
