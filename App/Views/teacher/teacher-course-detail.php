<?php
// Sayfa: lesson_management.php
?>
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
  const urlParts = window.location.pathname.split('/');
  const lessonId = urlParts[urlParts.length - 1];
  const contentEl = document.getElementById('lesson-management-content');
  const userId = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
  const sections = ['schedule','video','image','document','quiz'];

  contentEl.innerHTML = sections.map(type => createCard(type)).join('');

  fetch(`/akademikx/public/get-lesson-contents/${lessonId}`)
    .then(res => res.ok ? res.json() : Promise.reject())
    .then(data => updateContent(data))
    .catch(() => showError());

  fetch(`/akademikx/public/get-lesson-schedule/${lessonId}`)
    .then(res => res.ok ? res.json() : Promise.reject())
    .then(scheduleData => {
      const ul = contentEl.querySelector('[data-type="schedule"]');
      ul.innerHTML = generateListItems(scheduleData,'schedule');
    })
    .catch(() => {
      const ul = contentEl.querySelector('[data-type="schedule"]');
      ul.innerHTML = '<li class="list-group-item text-muted fst-italic">Yüklenemedi</li>';
    });

  fetch(`/akademikx/public/api/tests/by-lesson/${lessonId}`)
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(tests => Promise.all(tests.sort((a,b)=>a.id-b.id).map(item =>
      fetch(`/akademikx/public/api/tests/${item.id}/status`)
        .then(r=>r.ok?r.json():{attempted:false})
        .then(s=>({ ...item, attempted: s.attempted }))
    )))
    .then(tests => {
      const ul = contentEl.querySelector('[data-type="quiz"]');
      ul.innerHTML = tests.length
        ? tests.map(item => quizItem(item)).join('')
        : '<li class="list-group-item text-muted fst-italic">Test yok</li>';
    })
    .catch(() => {
      const ul = contentEl.querySelector('[data-type="quiz"]');
      ul.innerHTML = '<li class="list-group-item text-danger fst-italic">Yüklenemedi</li>';
    });

  ['uploadVideoModal','uploadImagesModal','uploadDocumentsModal','scheduleModal'].forEach(id => {
    const modal = document.getElementById(id);
    modal?.addEventListener('show.bs.modal', () => {
      document.getElementById('lessonIdInput').value = lessonId;
      document.getElementById('teacherIdInput')?.setAttribute('value', userId);
    });
  });

  function createCard(type) {
    const titles = {
      schedule:'📅 Ders Günleri & Saatleri',
      video:'🎥 Videolu İçerikler',
      image:'🖼️ Görsel İçerikler',
      document:'📄 Belgeler',
      quiz:'📝 Testler ve Sorular'
    };
    const buttons = {
      schedule:'<button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#scheduleModal">+ Gün & Saat Ekle</button>',
      video:'<button class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#uploadVideoModal">+ Video Ekle</button>',
      image:'<button class="btn btn-outline-warning w-100" data-bs-toggle="modal" data-bs-target="#uploadImagesModal">+ Görsel Ekle</button>',
      document:'<button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#uploadDocumentsModal">+ Belge Ekle</button>',
      quiz:`<button class="btn btn-info text-white w-100" onclick="location.href='/akademikx/public/teacher/tests/create?lesson_id=${lessonId}'">+ Test Ekle</button>`
    };
    return `<div class="col-md-6 mb-3"><div class="card shadow-sm h-100${type==='quiz'?' border-info':''}"><div class="card-body d-flex flex-column"><h5 class="card-title">${titles[type]}</h5><ul class="list-group list-group-flush mb-3 flex-grow-1" data-type="${type}"><li class="list-group-item text-muted fst-italic">Yükleniyor...</li></ul>${buttons[type]}</div></div></div>`;
  }

  function updateContent(data) {
    const grouped = {schedule:[],video:[],image:[],document:[]};
    data.forEach(i=>grouped[i.type]?.push(i));
    Object.keys(grouped).forEach(t=>{
      const ul = contentEl.querySelector(`[data-type="${t}"]`);
      ul.innerHTML = grouped[t].length
        ? grouped[t].map(item=>generateListItem(t,item)).join('')
        : '<li class="list-group-item text-muted fst-italic">Henüz eklenmemiş</li>';
    });
  }

  function generateListItems(data,type) {
    return data.length
      ? data.map(item=>generateListItem(type,item)).join('')
      : '<li class="list-group-item text-muted fst-italic">Henüz eklenmemiş</li>';
  }

  function generateListItem(type,item) {
    const lbl = escapeHtml(item.title||'');
    if(type==='schedule') return `<li class="list-group-item">${translateDay(item.day_of_week)} ${formatTime(item.start_time)} - ${formatTime(item.end_time)}</li>`;
    const enc=encodeURIComponent(item.file_url||item.id);
    const href={
      video:`../../lesson/play_lesson_video/${enc}`,
      image:`../../lesson/view_lesson_image/${enc}`,
      document:`../../lesson/view_lesson_document/${enc}`,
      quiz:`../../lesson/quiz_detail/${item.id}`
    }[type]||'#';
    return `<li class="list-group-item"><a class="stylish-link" href="${href}">${lbl}</a></li>`;
  }

  function quizItem(item) {
    const lbl=escapeHtml(item.title||'');
    return item.attempted
      ? `<li class="list-group-item d-flex justify-content-between align-items-center">${lbl}<span class="badge bg-success">Çözüldü</span></li>`
      : `<li class="list-group-item"><a class="stylish-link" href="/akademikx/public/lesson/quiz_detail/${item.id}">${lbl}</a></li>`;
  }

  function escapeHtml(s){return s?.replace(/[&<>"'`=\/]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','`':'&#x60;','=':'&#x3D;','/':'&#x2F;'})[c])||'';}
  function translateDay(d){const m={monday:'Pazartesi',tuesday:'Salı',wednesday:'Çarşamba',thursday:'Perşembe',friday:'Cuma',saturday:'Cumartesi',sunday:'Pazar'};return m[d?.toLowerCase()]||d||'';}
  function formatTime(t){return t?.substring(0,5)||'';}
  function showError(){sections.forEach(t=>{const ul=contentEl.querySelector(`[data-type="${t}"]`);ul.innerHTML='<li class="list-group-item text-danger fst-italic">Hata oluştu</li>';});}
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
