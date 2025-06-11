<?php
// Sayfa: about.php
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hakkımızda | AkademikX</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .hero {
      background: black;
      color: white;
      padding: 4rem 1rem;
      text-align: center;
    }
    .section-title {
      margin-top: 2rem;
      margin-bottom: 1rem;
    }
    .team img {
      border-radius: 50%;
    }
  </style>
</head>
<body>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>

  <section class="hero">
    <div class="container">
      <h1 class="display-4">AkademikX Nedir?</h1>
      <p class="lead">Eğitimi herkes için erişilebilir, kaliteli ve sürdürülebilir hale getirmek için yola çıktık.</p>
    </div>
  </section>

  <section class="container mt-5">
    <div class="row">
      <div class="col-md-6">
        <h2 class="section-title">Vizyonumuz</h2>
        <p>AkademikX, dijital eğitimde yenilikçi çözümler sunarak bireylerin bilgiye kolayca ulaşmasını amaçlar. Herkese açık, özgür ve kaliteli öğrenme ortamları oluşturmak en büyük hedefimizdir.</p>
      </div>
      <div class="col-md-6">
        <h2 class="section-title">Misyonumuz</h2>
        <p>Gelişen teknolojiyi eğitimle buluşturmak, öğrencileri dijital çağın gerekliliklerine hazırlamak ve yaşam boyu öğrenme kültürünü yaygınlaştırmak için çalışıyoruz.</p>
      </div>
    </div>
  </section>

  <section class="container mt-5">
    <h2 class="section-title text-center">Neler Sunuyoruz?</h2>
    <div class="row text-center">
      <div class="col-md-4 mb-4">
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5 class="card-title">Uzaktan Eğitim</h5>
            <p class="card-text">Her yerden erişilebilen dersler ve sınavlarla eğitim artık daha esnek.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5 class="card-title">Akademik Takip</h5>
            <p class="card-text">Öğrencilerin gelişimi, kayıt durumları ve ilerlemeleri detaylı olarak izlenebilir.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card border-0 shadow">
          <div class="card-body">
            <h5 class="card-title">Esnek Yapı</h5>
            <p class="card-text">Modüler sistem sayesinde öğretim üyeleri içeriklerini kolayca yönetebilir.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
