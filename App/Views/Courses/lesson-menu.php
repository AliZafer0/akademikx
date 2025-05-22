<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AkademikX | Ders İçeriği Yönetimi</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    .card-title {
      font-weight: 600;
      font-size: 1.25rem;
    }

    .stylish-link {
      display: block;
      padding: 0.75rem 1rem;
      color: #212529;
      text-decoration: none;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .stylish-link:hover {
      background-color: #f0f0f0;
      color: #0d6efd;
    }

    .stylish-link:active {
      background-color: #e2e6ea;
    }
  </style>
</head>
<body>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>

  <div class="container my-5">
    <h2 class="mb-4 text-center">Ders Yönetimi</h2>
    <div class="row g-4" id="lesson-content-container"></div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const urlParts = window.location.pathname.split('/');
      const lessonId = urlParts[urlParts.length - 1];
      const container = document.getElementById('lesson-content-container');
      if (!container) return;

      // Başlangıçta boş kartlar
      container.innerHTML = [
        createCard('📅 Ders Günleri & Saatleri', 'schedule'),
        createCard('🎥 Videolu İçerikler', 'video'),
        createCard('🖼️ Görsel İçerikler', 'image'),
        createCard('📄 Belgeler', 'document'),
        createCard('📝 Testler ve Sorular', 'quiz', lessonId)
      ].join('');

      // İçerik veri istekleri
      fetch(`/akademikx/public/get-lesson-contents/${lessonId}`)
        .then(res => res.json())
        .then(data => updateLessonContents(data, lessonId));

      fetch(`/akademikx/public/get-lesson-schedule/${lessonId}`)
        .then(res => res.json())
        .then(scheduleData => {
          const scheduleEl = container.querySelector('[data-type="schedule"]');
          if (scheduleEl) scheduleEl.innerHTML = generateListItems(scheduleData, 'schedule');
        });

      function updateLessonContents(data, lessonId) {
        const grouped = { video: [], image: [], document: [], quiz: [] };

        data.forEach(item => {
          if (grouped.hasOwnProperty(item.type)) {
            grouped[item.type].push(item);
          }
        });

        for (let type in grouped) {
          const listEl = container.querySelector(`[data-type="${type}"]`);
          if (listEl) {
            listEl.innerHTML = generateListItems(grouped[type], type, lessonId);
          }
        }
      }

      function createCard(title, type, lessonId = null) {
        let footerButton = '';

        if (type === 'quiz' && lessonId) {
          footerButton = `
            <div class="d-grid mt-2">
              <button class="btn btn-info text-white" onclick="window.location.href='question_add/add.php?lesson_id=${lessonId}'">
                + Yeni Soru / Test Ekle
              </button>
            </div>`;
        }

        return `
          <div class="col-md-6">
            <div class="card shadow-sm h-100 ${type === 'quiz' ? 'border-info' : ''}">
              <div class="card-body">
                <h5 class="card-title">${title}</h5>
                <ul class="list-group list-group-flush mb-3" data-type="${type}">
                  <li class="list-group-item text-muted fst-italic">İçerik yükleniyor...</li>
                </ul>
                ${footerButton}
              </div>
            </div>
          </div>`;
      }

      function generateListItems(items, type, lessonId = null) {
        if (!items || items.length === 0) {
          return `<li class="list-group-item text-muted fst-italic">İçerik henüz eklenmemiş</li>`;
        }

        return items.map(content => {
          if (type === 'schedule') {
            return `<li class="list-group-item">${translateDay(content.day_of_week)} ${formatTime(content.start_time)} - ${formatTime(content.end_time)}</li>`;
          }

          const label = escapeHtml(content.title || '');
          let link = '#';

          const base = `../lesson/view_lesson_${type}/${encodeURIComponent(content.file_url)}`;
          if (type === 'video') link = `../lesson/play_lesson_video/${encodeURIComponent(content.file_url)}`;
          else if (['image', 'document'].includes(type)) link = base;
          else if (type === 'quiz') link = `../lesson/quiz_detail/${content.id}`;

          return `<li class="list-group-item"><a class="stylish-link" href="${link}">${label}</a></li>`;
        }).join('');
      }

      function escapeHtml(text) {
        return text?.replace(/[&<>"'`=\/]/g, s => ({
          '&': '&amp;', '<': '&lt;', '>': '&gt;',
          '"': '&quot;', "'": '&#39;', '`': '&#x60;',
          '=': '&#x3D;', '/': '&#x2F;'
        })[s]) || '';
      }

      function translateDay(day) {
        const days = {
          monday: 'Pazartesi', tuesday: 'Salı', wednesday: 'Çarşamba',
          thursday: 'Perşembe', friday: 'Cuma', saturday: 'Cumartesi', sunday: 'Pazar'
        };
        return days[day?.toLowerCase()] || day;
      }

      function formatTime(timeStr) {
        return timeStr?.substring(0, 5) || '';
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
