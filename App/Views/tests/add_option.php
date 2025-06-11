<?php
$question_id = $_GET['question_id'] ?? '';
$test_id = $_GET['test_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AkademikX | Şık Ekle</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .form-section {
      max-width: 600px;
      margin: 50px auto;
      padding: 30px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>

  <div class="container">
    <div class="form-section">
      <h3 class="mb-4 text-center"><i class="bi bi-ui-checks"></i> Soruya Şık Ekle</h3>
      <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-<?= $_GET['msg'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <?= $_GET['msg'] === 'success' ? '✅ Şık başarıyla eklendi!' : '❌ Şık eklenirken bir hata oluştu.' ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
    </div>
    <?php endif; ?>

      <form method="POST" action="/akademikx/public/api/options/create">
        <input type="hidden" name="question_id" value="<?= htmlspecialchars($question_id) ?>">
        <input type="hidden" name="test_id" value="<?= htmlspecialchars($test_id) ?>">

        <div class="mb-3">
          <label for="option_text" class="form-label">Seçenek Metni</label>
          <input type="text" class="form-control" id="option_text" name="option_text" required>
        </div>

        <div class="mb-3">
          <label for="is_correct" class="form-label">Bu seçenek doğru mu?</label>
          <select class="form-select" id="is_correct" name="is_correct" required>
            <option value="1">Evet</option>
            <option value="0" selected>Hayır</option>
          </select>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Seçeneği Ekle
          </button>
        </div>
      </form>

      <div class="text-center mt-3">
        <a href="/akademikx/public/teacher/questions/create?test_id=<?= htmlspecialchars($test_id) ?>" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left-circle"></i> Teste Dön
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
