<?php
// Sayfa: view_lesson_document.php
?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AkademikX | </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>
  <div class="container mt-5">
      <div class="document-container">
          <div class="document-box">
              <div class="document">
                   <embed id="doc-embed" src="" type="application/pdf" width="100%" height="600px" />
              </div>
              <div class="description">
                  <h2 id="doc-title-header"></h2>
                  <p id="doc-description"></p>
              </div>
          </div>
      </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const urlParts = window.location.pathname.split('/');
      const filename = urlParts[urlParts.length - 1];
      const basePath = '../../uploads/documents/';

      fetch(`/akademikx/public/file-details/${filename}`)
        .then(res => {
          if (!res.ok) throw new Error('Doküman bilgisi alınamadı');
          return res.json();
        })
        .then(data => {
          const docData = data[0] || {};

          const embedEl = document.getElementById('doc-embed');
          if (embedEl && docData.file_url) {
            embedEl.src = basePath + docData.file_url;
          }

          const titleEl = document.getElementById('doc-title-header');
          if (titleEl) titleEl.textContent = docData.title || '';

          const descEl = document.getElementById('doc-description');
          if (descEl) descEl.textContent = docData.description || '';

          document.title = `AkademikX | ${docData.title || ''}`;
        })
        .catch(err => {
          console.error(err);
        });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
