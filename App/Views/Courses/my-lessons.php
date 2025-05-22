<?php
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
        .course-card {
            border-radius: 1rem;
            transition: transform 0.3s ease;
        }
        .course-card:hover {
            transform: scale(1.02);
        }
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        .progress-bar {
            transition: width 0.6s ease;
        }
    </style>
</head>
<body>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>
        <div class="container py-5">
  <h2 class="mb-4 text-center">📚 Kayıtlı Dersleriniz</h2>
  
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="courses-container">


  </div>
</div>
<script>
const userId = <?= json_encode($_SESSION['user_id']) ?>;


   document.addEventListener('DOMContentLoaded', () => {
    fetch(`/akademikx/public/Get-Enrolled-User/${userId}`)
    .then(response => response.json())
    .then(courses => {
      const container = document.getElementById('courses-container');
      if (!container) {
        console.error('courses-container bulunamadı!');
        return;
      }

      container.innerHTML = ''; // varsa önce içeriği temizle

      courses.forEach(course => {
        const card = `
            <div class="col">
                <div class="card course-card shadow-sm">
                    <img src="uploads/images/${escapeHtml(course.img_url)}" class="card-img-top" alt="Python Dersi">
                    <div class="card-body">
                    <h5 class="card-title">${escapeHtml(course.title)}</h5>
                    <p class="card-text text-muted">${escapeHtml(course.description)}</p>
                    <div class="mb-2">
                        <small class="text-muted">İlerleme: %65</small>
                        <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%;"></div>
                        </div>
                    </div>
                    <a href="lesson_menu/${escapeHtml(course.id)}" class="btn btn-outline-primary w-100 mt-2">Derse Git</a>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', card);
      });
    })
    .catch(err => {
      console.error('Veri Alınırken Hata:', err);
    });

  function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>"'`=\/]/g, s => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
      '`': '&#x60;',
      '=': '&#x3D;',
      '/': '&#x2F;'
    })[s]);
  }
});

</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>