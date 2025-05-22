<?php use App\Helpers\AuthHelper; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title id="page-title">AkademikX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        .course-banner {
            max-height: 400px;
            object-fit: cover;
            border-radius: 1rem;
        }
        .register-btn {
            transition: all 0.3s ease-in-out;
        }
        .register-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>

  <div class="container py-5">
    <div class="row g-4">

      <!-- Sol kısım: görsel -->
      <div class="col-md-6">
        <img id="course-image" src="" alt="Ders Görseli" class="img-fluid course-banner shadow-sm" />
      </div>

      <!-- Sağ kısım: ders bilgisi -->
      <div class="col-md-6">
        <h1 class="display-6" id="course-title">Yükleniyor...</h1>
        <hr />
        <h5>Ders Açıklaması</h5>
        <p id="course-description"></p>

        <ul class="list-group mb-4">
          <li class="list-group-item"><strong>Kategori ID:</strong> <span id="course-category"></span></li>
          <li class="list-group-item"><strong>Oluşturan:</strong> <span id="course-created-by"></span></li>
          <li class="list-group-item"><strong>Oluşturulma:</strong> <span id="course-created-at"></span></li>
          <li class="list-group-item"><strong>İçerik Sayısı:</strong> <?=htmlspecialchars($lesson_contents['count'] ?? 0)?> Aktif İçerik</li>
        </ul>

        <?php if (AuthHelper::isLoggedIn()): ?>
                <div class="alert alert-success d-flex align-items-center p-4 rounded-3 shadow-sm" role="alert" id="alert_register">
                    <svg xmlns="http://www.w3.org/2000/svg" class="me-2" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 0 0 8a8 8 0 0 0 16 0zM7.97 11.03a.75.75 0 0 1-1.06 0L4.47 8.59a.75.75 0 1 1 1.06-1.06l1.94 1.94 4.47-4.47a.75.75 0 0 1 1.06 1.06L7.97 11.03z"/>
                    </svg>
                    <div class="fw-bold fs-5">Kayıt başarıyla tamamlandı!</div>
                </div>
              <form action="addEnrollment" method="POST" id="form_register">
                    <input type="hidden" name="lesson_id" id="lesson_id" value="<?=htmlspecialchars($lesson_id ?? '')?>" />
                    <input type="hidden" name="user_id" id="user_id"value="<?=htmlspecialchars($_SESSION['user_id'])?>" />
                    <button type="submit" class="btn btn-primary btn-lg w-100 register-btn">📘 Kayıt Ol</button>
                </form>
          <?php endif; ?>

      </div>
    </div>
  </div>
  
<script>
  const urlParts = window.location.pathname.split('/');
  const courseId = urlParts[urlParts.length - 1];

  const userId = <?= isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null' ?>;

  // Kurs verisini çekiyoruz
  fetch(`/akademikx/public/course-detail-json/${courseId}`)
    .then(response => {
      if (!response.ok) throw new Error('Ağdan veri alınamadı');
      return response.json();
    })
    .then(data => {
      if (!data || !data.length) throw new Error('Veri bulunamadı');

      const course = data[0];

      document.getElementById('course-title').innerText = course.title;
      document.getElementById('course-description').innerText = course.description;
      document.getElementById('course-image').src = `../uploads/images/${course.img_url}`;
      document.getElementById('course-category').innerText = course.category_id;
      document.getElementById('course-created-by').innerText = course.created_by;
      document.getElementById('course-created-at').innerText = course.created_at;

      const userInput = document.getElementById('user_id');
      const lessonInput = document.getElementById('lesson_id');
      if (userInput && lessonInput) {
        userInput.value = userId;
        lessonInput.value = course.id;
      }
    })
    .catch(error => {
      const courseContainer = document.getElementById('course-container');
      if (courseContainer) {
        courseContainer.innerText = "Kurs verisi alınamadı. Lütfen tekrar deneyiniz.";
      }
      console.error('Hata:', error);
    });

  // Kullanıcı giriş yaptıysa kayıt kontrolünü yap
  if (userId) {
    fetch(`/akademikx/public/Student-Enrolled/${userId}/${courseId}`)
      .then(res => res.json())
      .then(data => {
        const form = document.getElementById('form_register');
        const alert = document.getElementById('alert_register');

        if (!form || !alert) return;

        if (data.isEnrolled) {
          form.style.display = 'none';
          alert.style.display = 'block';
          console.log("Bu eleman Kayıtlı");
        } else {
          alert.style.setProperty('display', 'none', 'important');
          form.style.display = 'block';
          console.log("Bu eleman Kayıtlı değil");
        }
      })
      .catch(err => console.error("Hata:", err));
  }
</script>

<script>
  
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" 
          integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>
