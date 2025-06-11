<?php
// Sayfa: teacher_panel.php
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Öğretmen Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card-hover:hover {
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            cursor: pointer;
        }
    </style>
</head>
<body>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>

  <div class="container py-4">
    <h2 class="mb-4">Merhaba Öğretmenim 👨‍🏫</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card text-white bg-primary">
          <div class="card-body">
            <h5 class="card-title">Toplam Öğrenci</h5>
            <p class="card-text display-6" id="student-count">Yükleniyor...</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-success">
          <div class="card-body">
            <h5 class="card-title">En Yakın Ders</h5>
            <p class="card-text">Yükleniyor...</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-info">
          <div class="card-body">
            <h5 class="card-title">Ders Sayısı</h5>
            <p class="card-text display-6" id="lesson_count">Yükleniyor...</p>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-5">
      <h4>Sorumlu Olduğunuz Dersler</h4>
      <div class="row row-cols-1 row-cols-md-3 g-4 mt-2" id="courses-row">
        <!-- Ders kartları JS ile yüklenecek -->
      </div>
    </div>
  </div>

<script>
const userId = <?= json_encode($_SESSION['user_id'] ?? null) ?>;

document.addEventListener("DOMContentLoaded", () => {
  if (!userId) return;

  fetch(`/akademikx/public/api/teacher-courses/${userId}`)
    .then(res => res.json())
    .then(courses => {
      document.getElementById('lesson_count').textContent = courses.length;
      const row = document.getElementById('courses-row');
      row.innerHTML = '';

      courses.forEach(course => {
        const col = document.createElement('div');
        col.className = 'col';
        col.innerHTML = `
          <div class="card card-hover h-100" onclick="location.href='/akademikx/public/teacher-panel/course-detail/${course.id}'">
            <div class="card-body">
              <h5 class="card-title">${course.title}</h5>
              <p class="card-text student-count" data-course-id="${course.id}">Öğrenci Sayısı: Yükleniyor...</p>
              <p class="card-text">Son düzenleme: ${course.created_at ?? '—'}</p>
            </div>
          </div>
        `;
        row.appendChild(col);

        fetch(`/akademikx/public/api/student-count/${course.id}`)
          .then(r => r.json())
          .then(data => {
            const el = col.querySelector(`.student-count[data-course-id="${course.id}"]`);
            if (el) el.textContent = `Öğrenci Sayısı: ${data.count ?? '—'}`;
          });
      });
    });

  // toplam öğrenci sayısını yüklemek için örnek: tüm kurslardaki toplam öğrenci
  // Burada kendi mantığınıza göre güncelleyebilirsiniz
  fetch(`/akademikx/public/api/student-count/${userId}`)
    .then(res => res.json())
    .then(data => {
      document.getElementById('student-count').textContent = data.count ?? '—';
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
