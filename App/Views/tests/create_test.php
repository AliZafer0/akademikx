<?php 
  // URL’den gelen lesson_id parametresi ile course_id’yi otomatik al
  $courseId = $_GET['lesson_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AkademikX | Yeni Test Ekle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
    <?php include __DIR__ . '/../partials/navbar.php'; ?>

  <div class="container my-5">
             <a href="/akademikx/public/teacher-panel" class="btn btn-light btn-sm">
          <i class="bi bi-arrow-left me-1"></i>Öğretmen Paneline Dön
        </a>
    <div class="card mx-auto" style="max-width: 600px;">
      <div class="card-header bg-primary text-white">
        <i class="bi bi-plus-circle me-2"></i>Yeni Test Ekle

      </div>
      <div class="card-body">
        <form method="POST" action="/akademikx/public/api/tests/create">
          <!-- Ders ID gizli, otomatik -->
          <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($courseId); ?>" />

          <div class="mb-3">
            <label for="testTitle" class="form-label">Test Başlığı</label>
            <input type="text" class="form-control" id="testTitle" name="title"
                   placeholder="Test adı girin" required>
          </div>
          <div class="mb-3">
            <label for="testDuration" class="form-label">Süre (dakika)</label>
            <input type="number" class="form-control" id="testDuration" name="duration"
                   placeholder="Örn. 30" min="1">
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-success btn-lg">
              <i class="bi bi-save2 me-1"></i> Testi Oluştur
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-ENjdO4Dr2bkBIFxQpeoA6VZTfNR7Eep9jlj/0V2Y5TkP5U5KkN1p2HEQK7f5Wvo2"
          crossorigin="anonymous"></script>
</body>
</html>
