<?php use App\Helpers\AuthHelper; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AkademikX | Ders Yönetimi</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
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
    <h2 class="mb-4 text-center" id="lesson-title">Ders Yönetimi</h2>
    <div class="row g-4" id="lesson-management-content"></div>
  </div>

  <?php include_once __DIR__ . '/../partials/modals/upload_documents_modal.php'; ?>
  <?php include_once __DIR__ . '/../partials/modals/upload_images_modal.php'; ?>
  <?php include_once __DIR__ . '/../partials/modals/upload_video_modal.php'; ?>
  <?php include_once __DIR__ . '/../partials/modals/time_add_modal.php'; ?>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // URL'den lessonId'yi al
    const urlParts = window.location.pathname.split('/');
    const lessonId = urlParts[urlParts.length - 1];
    const contentEl = document.getElementById('lesson-management-content');
  const userIdFromSession = <?php echo json_encode($_SESSION['user_id'] ?? null); ?>;

    // Input elemanlarını al
    const lessonInput = document.getElementById('lessonIdInput');

    // Inputlara değer ata
    if (lessonInput) lessonInput.value = lessonId;

    console.log('Lesson ID:', lessonId);

    if (!contentEl) {
      console.error('İçerik elementi bulunamadı: #lesson-management-content');
      return;
    }

    // Gösterilecek içerik bölümleri
    const sections = ['schedule', 'video', 'image', 'document', 'quiz'];
    contentEl.innerHTML = sections.map(type => createCard(type)).join('');

    // Ders içeriklerini çek
    fetch(`/akademikx/public/get-lesson-contents/${lessonId}`)
      .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
      })
      .then(data => updateContent(data))
      .catch(err => {
        console.error('İçerik getirme hatası:', err);
        showErrorMessages();
      });

    // Ders programını çek
    fetch(`/akademikx/public/get-lesson-schedule/${lessonId}`)
      .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
      })
      .then(scheduleData => {
        const scheduleEl = contentEl.querySelector('[data-type="schedule"]');
        if (scheduleEl) scheduleEl.innerHTML = generateListItems(scheduleData, 'schedule');
      })
      .catch(err => {
        console.error('Ders programı getirme hatası:', err);
        const scheduleEl = contentEl.querySelector('[data-type="schedule"]');
        if (scheduleEl) scheduleEl.innerHTML = `<li class="list-group-item text-muted fst-italic">Ders programı yüklenemedi</li>`;
      });

    // Modal açıldığında lesson ve teacher id'leri tekrar güncelle
    const modals = [
      'uploadVideoModal',
      'uploadImagesModal',
      'uploadDocumentsModal',
      'scheduleModal'
    ];

    modals.forEach(modalId => {
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.addEventListener('show.bs.modal', () => {
          if (lessonInput) lessonInput.value = lessonId;
          if (teacherInput) teacherInput.value = userIdFromSession;
        });
      }
    });

    // Yardımcı fonksiyonlar

    function createCard(type) {
      const titleMap = {
        schedule: '📅 Ders Günleri & Saatleri',
        video: '🎥 Videolu İçerikler',
        image: '🖼️ Görsel İçerikler',
        document: '📄 Belgeler',
        quiz: '📝 Testler ve Sorular'
      };

      let buttonHtml = '';
      if (type === 'schedule') {
        buttonHtml = `<button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#scheduleModal">+ Gün & Saat Ekle</button>`;
      } else if (type === 'video') {
        buttonHtml = `<button class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#uploadVideoModal">+ Video Ekle</button>`;
      } else if (type === 'image') {
        buttonHtml = `<button class="btn btn-outline-warning w-100" data-bs-toggle="modal" data-bs-target="#uploadImagesModal">+ Görsel Ekle</button>`;
      } else if (type === 'document') {
        buttonHtml = `<button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#uploadDocumentsModal">+ Belge Ekle</button>`;
      } else if (type === 'quiz') {
        buttonHtml = `<button class="btn btn-info text-white w-100" onclick="window.location.href='question_add/add.php?lesson_id=${lessonId}'">+ Yeni Soru / Test Ekle</button>`;
      }

      return `
        <div class="col-md-6 mb-3">
          <div class="card shadow-sm h-100 ${type === 'quiz' ? 'border-info' : ''}">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">${titleMap[type]}</h5>
              <ul class="list-group list-group-flush mb-3 flex-grow-1" data-type="${type}">
                <li class="list-group-item text-muted fst-italic">İçerik yükleniyor...</li>
              </ul>
              ${buttonHtml}
            </div>
          </div>
        </div>`;
    }

    function updateContent(data) {
      const grouped = { schedule: [], video: [], image: [], document: [], quiz: [] };
      data.forEach(item => {
        if (grouped.hasOwnProperty(item.type)) grouped[item.type].push(item);
      });

      for (let type in grouped) {
        const ul = document.querySelector(`[data-type="${type}"]`);
        if (!ul) continue;

        if (grouped[type].length === 0) {
          ul.innerHTML = `<li class="list-group-item text-muted fst-italic">İçerik henüz eklenmemiş</li>`;
          continue;
        }

        ul.innerHTML = grouped[type].map(item => generateListItem(type, item)).join('');
      }
    }

    function generateListItems(data, type) {
      if (!data || data.length === 0) {
        return `<li class="list-group-item text-muted fst-italic">İçerik henüz eklenmemiş</li>`;
      }
      return data.map(item => generateListItem(type, item)).join('');
    }

    function generateListItem(type, item) {
      const label = escapeHtml(item.title || 'Başlıksız');
      let link = '#';

      if (type === 'schedule') {
        return `<li class="list-group-item">${translateDay(item.day_of_week)} ${formatTime(item.start_time)} - ${formatTime(item.end_time)}</li>`;
      }

      const encoded = encodeURIComponent(item.file_url);
      if (type === 'video') link = `../../lesson/play_lesson_video/${encoded}`;
      else if (type === 'image') link = `../../lesson/view_lesson_image/${encoded}`;
      else if (type === 'document') link = `../../lesson/view_lesson_document/${encoded}`;
      else if (type === 'quiz') link = `../../lesson/quiz_detail/${item.id}`;

      return `<li class="list-group-item"><a class="stylish-link" href="${link}" target="_blank" rel="noopener noreferrer">${label}</a></li>`;
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
        monday: 'Pazartesi',
        tuesday: 'Salı',
        wednesday: 'Çarşamba',
        thursday: 'Perşembe',
        friday: 'Cuma',
        saturday: 'Cumartesi',
        sunday: 'Pazar'
      };
      return days[day?.toLowerCase()] || day || '';
    }

    function formatTime(timeStr) {
      return timeStr?.substring(0, 5) || '';
    }

    function showErrorMessages() {
      sections.forEach(type => {
        const ul = document.querySelector(`[data-type="${type}"]`);
        if (ul) {
          ul.innerHTML = `<li class="list-group-item text-danger fst-italic">İçerik yüklenirken hata oluştu.</li>`;
        }
      });
    }
  });
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
