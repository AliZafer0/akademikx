<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | <?=htmlspecialchars($image['title'])?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh; /* Tam ekran yüksekliği */
        }

        .image-box {
            width: 85%; /* image alanı ekranın %85'i kadar genişlik */
            max-width: 2200px; /* En fazla 1200px genişlik */
            height: 70vh; /* image alanının yüksekliği ekranın %90'ı kadar */
            display: flex;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
        }

        .image-box .image {
            width: 60%; /* image alanı sol tarafta geniş olacak */
            height: 100%;
            background-color: #000;
        }

        .image-box .description {
            width: 40%; /* Açıklama alanı sağda daha küçük */
            padding: 20px;
            background-color: #f7f7f7;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .description h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
        }

        .description p {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .description .btn {
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .description .btn:hover {
            background-color: #0056b3;
        }
        
    </style>

</head>
<body>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>
    <div class="container mt-5">
        <div class="container">
            <div class="image-container">
                <div class="image-box">
                    <div class="image">
                         <img id="my-thumb" src="">
                    </div>
                    <div class="description">
                        <h2></h2>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const urlParts = window.location.pathname.split('/');
  const filename = urlParts[urlParts.length - 1];

  fetch(`/akademikx/public/file-details/${filename}`)
    .then(res => {
      if (!res.ok) throw new Error('Image bilgisi alınamadı');
      return res.json();
    })
    .then(data => {
      const imageData = data[0] || {};

      // Görsel dosya yolunu oluştur
      const imgUrl = imageData.file_url ? `../../uploads/images/${imageData.file_url}` : '';

      // img elementini güncelle
      const imgEl = document.getElementById('my-thumb');
      if (imgEl) imgEl.src = imgUrl;

      // Sayfa başlığını ayarla
      document.title = `AkademikX | ${imageData.title || ''}`;

      // Başlık ve açıklama elemanlarını güncelle
      const titleEl = document.querySelector('.description h2');
      if (titleEl) titleEl.textContent = imageData.title || '';

      const descEl = document.querySelector('.description p');
      if (descEl) descEl.textContent = imageData.description || '';
    })
    .catch(err => {
      console.error(err);
      // İstersen burada kullanıcıya hata mesajı gösterebilirsin
    });
});
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>