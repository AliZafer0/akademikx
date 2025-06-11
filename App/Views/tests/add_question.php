<?php
$test_id = $_GET['test_id'] ?? '';
$content_id = $_GET['content_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AkademikX | Teste Soru Ekle</title>
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
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'soru_eklendi'): ?>
  <div class="alert alert-success">Soru başarıyla eklendi.</div>
<?php endif; ?>

  <div class="container">
    <div class="form-section">
      <h3 class="mb-4 text-center"><i class="bi bi-plus-circle"></i> Teste Soru Ekle</h3>
      <form method="POST" action="/akademikx/public/api/questions/create">
        <input type="hidden" name="test_id" value="<?= htmlspecialchars($test_id) ?>">
        <input type="hidden" name="content_id" value="<?= htmlspecialchars($content_id) ?>">

        <div class="mb-3">
          <label for="question_text" class="form-label">Soru Metni</label>
          <textarea class="form-control" id="question_text" name="question_text" rows="3" required></textarea>
        </div>

        <div class="mb-3">
          <label for="type" class="form-label">Soru Türü</label>
          <select class="form-select" id="type" name="type" required>
            <option value="multiple">Çoktan Seçmeli</option>
            <option value="open">Açık Uçlu</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="points" class="form-label">Puan</label>
          <input type="number" class="form-control" id="points" name="points" value="1" min="1" required>
        </div>

        <div class="mb-3">
          <label for="order_no" class="form-label">Sıra No</label>
          <input type="number" class="form-control" id="order_no" name="order_no" value="1" min="1" required>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Soruyu Ekle
          </button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
