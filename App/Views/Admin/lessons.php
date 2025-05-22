<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AkademikX | Admin Panel</title>

  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link href="/akademikx/public/assets/css/admin_nav.css" rel="stylesheet" />
  <link rel="stylesheet" href="/akademikx/public/assets/css/btn.css" />

  <style>
    /* Ekstra stiller buraya */
  </style>
</head>
<body>
  <?php include __DIR__ . '/../partials/admin_navbar.php'; ?>

  <div class="page mt-4">
    <div class="table-header d-flex py-3 px-4 mb-2">
      <select class="form-select w-auto" id="limitSelect">
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
        <option value="40">40</option>
        <option value="50">50</option>
      </select>

      <form role="search">
        <input class="table-search" type="search" id="searchInput" placeholder="Ders Ara.." aria-label="Search" />
      </form>

      <a href="/akademikx/public/admin/lessons/add_lessons" class="btn header-yesil-btn ms-auto">Yeni Ders Ekle</a>

      <div class="dropdown">
        <a href="#" class="btn header-mor-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          Dışa Aktar <i class="fa-solid fa-download"></i>
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item export-excel" href="#"><i class="fa-solid fa-file-excel"></i> Excel Olarak İndir</a></li>
          <li><a class="dropdown-item export-pdf" href="#"><i class="fa-solid fa-file-pdf"></i> PDF Olarak İndir</a></li>
        </ul>
      </div>
    </div>

    <div class="table-wrapper mt-3 mx-4">
      <table class="public-table table-striped mb-2">
        <thead>
          <tr>
            <th scope="col">
              <div class="checkbox-wrapper-42">
                <input type="checkbox" id="select-all" class="custom-checkbox">
                <label class="cbx" for="select-all"></label>
              </div>
            </th>
            <th scope="col">ID</th>
            <th scope="col">Başlık</th>
            <th scope="col">Açıklama</th>
            <th scope="col">Kategori</th>
            <th scope="col">Öğretmen</th>
            <th scope="col">Oluşturma Tarihi</th>
            <th scope="col">İşlem</th>
          </tr>
        </thead>
        <tbody id="lessonsTableBody">
          <!-- Dinamik içerik JS ile doldurulacak -->
        </tbody>
      </table>

      <div id="paginationWrapper"></div>
    <?php include __DIR__ . '/../partials/modals/lesson_edit_modal.php'; ?>
    </div>
  </div>

  <?php include __DIR__ . '/../partials/nav_js.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const tableBody = document.querySelector('tbody');
  const searchInput = document.querySelector('.table-search');
  const limitSelect = document.querySelector('select.form-select');

  let currentPage = 1;

  async function fetchLessons(page = 1, limit = 10, search = '') {
    const url = new URL('/akademikx/public/api/admin/get-lessons.php', window.location.origin);
    url.searchParams.append('page', page);
    url.searchParams.append('limit', limit);
    url.searchParams.append('search', search);

    console.log("API çağrısı yapılıyor:", url.toString());

    try {
      const response = await fetch(url);
      console.log("Response geldi:", response);

      if (!response.ok) throw new Error('Network response was not ok: ' + response.status);

      const data = await response.json();
      console.log("API'den gelen JSON:", data);

      if (data.status !== 'success') {
        console.error('API status başarısız:', data);
        tableBody.innerHTML = '<tr><td colspan="8">Veri alınamadı, API başarısız döndü.</td></tr>';
        return;
      }

      renderLessons(data.data);

    } catch (error) {
      console.error('Fetch hatası:', error);
      tableBody.innerHTML = '<tr><td colspan="8">Veri alınırken hata oluştu.</td></tr>';
    }
  }

  function renderLessons(lessons) {
    console.log("renderLessons fonksiyonuna gelen veri:", lessons);

    if (!lessons || lessons.length === 0) {
      tableBody.innerHTML = '<tr><td colspan="8">Kayıt bulunamadı.</td></tr>';
      return;
    }

    tableBody.innerHTML = lessons.map(lesson => `
      <tr>
        <td>
          <div class="checkbox-wrapper-42">
            <input type="checkbox" id="checkbox-${lesson.id}" class="custom-checkbox">
            <label class="cbx" for="checkbox-${lesson.id}"></label>
          </div>
        </td>
        <td>${lesson.id}</td>
        <td>${escapeHtml(lesson.title)}</td>
        <td>${escapeHtml(truncateDescription(lesson.description))}</td>
        <td>${escapeHtml(lesson.category)}</td>
        <td>${escapeHtml(lesson.teacher)}</td>
        <td>${lesson.created_at ? lesson.created_at : '-'}</td>
        <td>
          <a class="btn morumsu-btn" data-bs-toggle="modal" data-bs-target="#editLessonModal"
            data-id="${lesson.id}"
            data-title="${escapeHtml(lesson.title)}"
            data-desc="${escapeHtml(lesson.description)}"
            data-category="${escapeHtml(lesson.category)}"
            data-teacher="${escapeHtml(lesson.teacher)}"
            data-created="${lesson.created_at ? lesson.created_at : '-'}">
            <i class="fa-regular fa-eye"></i>
          </a>
          <a href="#" class="btn yesil-btn download-user"></a>
          <a href="delete_user.php?id=${lesson.id}" class="btn kirmizi-btn"
            onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
            <i class="fas fa-trash"></i>
          </a>
        </td>
      </tr>
    `).join('');
  }

  function truncateDescription(desc) {
    if (!desc) return '';
    return desc.length > 50 ? desc.substring(0, 50) + '...' : desc;
  }

  function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  // Arama inputu ve limit select değişince verileri tekrar getir
  searchInput.addEventListener('input', () => {
    currentPage = 1;
    fetchLessons(currentPage, limitSelect.value, searchInput.value);
  });

  limitSelect.addEventListener('change', () => {
    currentPage = 1;
    fetchLessons(currentPage, limitSelect.value, searchInput.value);
  });

  // İlk sayfa yüklenirken veri çek
  fetchLessons(currentPage, limitSelect.value, searchInput.value);
});
</script>

</body>
</html>