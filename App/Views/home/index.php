<?php use App\Helpers\CourseHelper; ?>
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
         transition: transform 0.3s, box-shadow 0.3s;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .card-img-top {
            height: 180px;
            object-fit: cover;
        }
    </style>
</head>
<body>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container py-5">
<div class="row g-4" id="courses-container"></div>

    </div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  fetch('/akademikx/public/courses-json')
    .then(response => response.json())
    .then(courses => {
      const container = document.getElementById('courses-container');
      if (!container) {
        console.error('courses-container bulunamadı!');
        return;
      }

      container.innerHTML = ''; // varsa önce içeriği temizle

      courses.forEach(course => {
        // enroll durumuna göre buton stili ve yazısı
        const isEnrolled = course.enroll === true;
        const btnClass = isEnrolled ? 'btn btn-success w-100' : 'btn btn-outline-primary w-100';
        const btnText = isEnrolled ? 'Kayıtlısın' : 'Derse Git';

        const card = `
          <div class="col-md-4">
            <div class="card course-card h-100">
              <img src="uploads/images/${escapeHtml(course.img_url)}" class="card-img-top" alt="Ders 1">
              <div class="card-body">
                <h5 class="card-title">${escapeHtml(course.title)}</h5>
                <p class="card-text">${escapeHtml(course.description)}</p>
              </div>
              <div class="card-footer bg-white border-0">
                <a href="lesson/${encodeURIComponent(course.id)}" class="${btnClass}">${btnText}</a>
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