<?php
// Sayfa: lesson-content.php
?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AkademikX | Ders İçeriği Yönetimi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    .card-title { font-weight: 600; font-size: 1.25rem; }
    .stylish-link {
      display: block; padding: .75rem 1rem;
      color: #212529; text-decoration: none;
      transition: background-color .3s, color .3s;
    }
    .stylish-link:hover { background: #f0f0f0; color: #0d6efd; }
    .stylish-link:active { background: #e2e6ea; }
  </style>
</head>
<body>
  <div class="container my-5">
    <h2 class="mb-4 text-center">Ders Yönetimi</h2>
    <div class="row g-4" id="lesson-content-container"></div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const parts = window.location.pathname.split('/');
    const lessonId = parts[parts.length - 1];
    const container = document.getElementById('lesson-content-container');

    container.innerHTML = [
      createCard('📅 Ders Günleri & Saatleri', 'schedule'),
      createCard('🎥 Videolu İçerikler', 'video'),
      createCard('🖼️ Görsel İçerikler', 'image'),
      createCard('📄 Belgeler', 'document'),
      createCard('📝 Testler ve Sorular', 'quiz')
    ].join('');

    fetch(`/akademikx/public/get-lesson-contents/${lessonId}`)
      .then(res => res.ok ? res.json() : Promise.reject())
      .then(data => {
        const grouped = { schedule: [], video: [], image: [], document: [] };
        data.forEach(item => {
          if (grouped[item.type]) grouped[item.type].push(item);
        });
        Object.keys(grouped).forEach(type => {
          const ul = container.querySelector(`[data-type="${type}"]`);
          if (ul) ul.innerHTML = generateListItems(grouped[type], type);
        });
      })
      .catch(() => {
        ['schedule','video','image','document'].forEach(type => {
          const ul = container.querySelector(`[data-type="${type}"]`);
          if (ul) ul.innerHTML = `<li class="list-group-item text-danger fst-italic">Yüklenemedi</li>`;
        });
      });

    fetch(`/akademikx/public/get-lesson-schedule/${lessonId}`)
      .then(res => res.ok ? res.json() : Promise.reject())
      .then(data => {
        const ul = container.querySelector('[data-type="schedule"]');
        if (ul) ul.innerHTML = generateListItems(data, 'schedule');
      })
      .catch(() => {});

    fetch(`/akademikx/public/api/tests/by-lesson/${lessonId}`)
      .then(res => res.ok ? res.json() : Promise.reject())
      .then(testData => {
        testData.sort((a, b) => a.id - b.id);
        return Promise.all(testData.map(item =>
          fetch(`/akademikx/public/api/tests/${item.id}/status`)
            .then(r => r.ok ? r.json() : { attempted: false })
            .then(status => ({ ...item, attempted: status.attempted }))
        ));
      })
      .then(tests => {
        const ul = container.querySelector('[data-type="quiz"]');
        if (!ul) return;
        if (tests.length === 0) {
          ul.innerHTML = `<li class="list-group-item text-muted fst-italic">Test henüz eklenmemiş</li>`;
        } else {
          ul.innerHTML = tests.map(item => {
            const lbl = escapeHtml(item.title || 'Başlıksız');
            if (item.attempted) {
              return `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  ${lbl}
                  <span class="badge bg-success">Çözüldü</span>
                </li>`;
            }
            return `
              <li class="list-group-item">
                <a class="stylish-link" href="/akademikx/public/lesson/quiz_detail/${item.id}">
                  ${lbl}
                </a>
              </li>`;
          }).join('');
        }
      })
      .catch(() => {
        const ul = container.querySelector('[data-type="quiz"]');
        if (ul) ul.innerHTML = `<li class="list-group-item text-danger fst-italic">Yüklenemedi</li>`;
      });

    function createCard(title, type) {
      return `
        <div class="col-md-6">
          <div class="card shadow-sm h-100 ${type==='quiz'?'border-info':''}">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">${title}</h5>
              <ul class="list-group list-group-flush mb-3" data-type="${type}">
                <li class="list-group-item text-muted fst-italic">Yükleniyor...</li>
              </ul>
            </div>
          </div>
        </div>`;
    }
    function generateListItems(items, type) {
      if (!items.length) {
        return `<li class="list-group-item text-muted fst-italic">Henüz eklenmemiş</li>`;
      }
      return items.map(item => {
        if (type==='schedule') {
          return `<li class="list-group-item">${translateDay(item.day_of_week)} ${formatTime(item.start_time)} - ${formatTime(item.end_time)}</li>`;
        }
        const lbl = escapeHtml(item.title || '');
        let href = '#';
        const enc = encodeURIComponent(item.file_url || item.id);
        if (type==='video') href = `../lesson/play_lesson_video/${enc}`;
        if (type==='image') href = `../lesson/view_lesson_image/${enc}`;
        if (type==='document') href = `../lesson/view_lesson_document/${enc}`;
        return `<li class="list-group-item"><a class="stylish-link" href="${href}">${lbl}</a></li>`;
      }).join('');
    }
    function escapeHtml(s) {
      return s?.replace(/[&<>"'`=\/]/g, c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','`':'&#x60;','=':'&#x3D;','/':'&#x2F;'}[c]))||'';
    }
    function translateDay(d) {
      const m={monday:'Pazartesi',tuesday:'Salı',wednesday:'Çarşamba',thursday:'Perşembe',friday:'Cuma',saturday:'Cumartesi',sunday:'Pazar'};
      return m[d?.toLowerCase()]||d||'';
    }
    function formatTime(t){ return t?.substring(0,5)||''; }
  });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
