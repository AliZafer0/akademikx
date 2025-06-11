<?php
// Sayfa: tests.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Test Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="/akademikx/public/assets/css/admin_nav.css" rel="stylesheet" />
    <link rel="stylesheet" href="/akademikx/public/assets/css/btn.css" />
    <link href="/akademikx/public/assets/css/pagination.css" rel="stylesheet">
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

            <input class="table-search ms-3" type="search" id="searchInput" placeholder="Test Ara..">

            <a href="/akademikx/public/teacher/tests/create" class="btn header-yesil-btn ms-auto">Yeni Test Ekle</a>

            <div class="dropdown ms-2">
                <a href="#" class="btn header-mor-btn dropdown-toggle" data-bs-toggle="dropdown">
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
                        <th><input type="checkbox" id="select-all"></th>
                        <th>ID</th>
                        <th>Başlık</th>
                        <th>Süre (dk)</th>
                        <th>Oluşturma Tarihi</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody id="testTableBody"></tbody>
            </table>
            <nav>
                <ul class="pagination" id="pagination"></ul>
            </nav>
        </div>
    </div>

    <script>
    let currentPage = 1;

    function fetchTests() {
        const limit = document.getElementById('limitSelect').value;
        const search = encodeURIComponent(document.getElementById('searchInput').value.trim());
        fetch(`/akademikx/public/api/admin/get-tests?page=${currentPage}&limit=${limit}&search=${search}`)
            .then(res => {
                if (!res.ok) throw new Error(res.status);
                return res.json();
            })
            .then(data => {
                const tests = data.tests || data;
                const totalPages = data.totalPages || 1;
                renderTests(tests);
                renderPagination(totalPages);
            })
            .catch(err => {
                console.error(err);
                const tbody = document.getElementById('testTableBody');
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Veri yüklenemedi.</td></tr>';
            });
    }

    function renderTests(tests) {
        const tbody = document.getElementById('testTableBody');
        tbody.innerHTML = tests.map(test => `
            <tr>
                <td><input type="checkbox" class="test-checkbox"></td>
                <td>${test.id}</td>
                <td>${escapeHtml(test.title)}</td>
                <td>${test.duration ?? '-'}</td>
                <td>${test.created_at}</td>
                <td>
                    <a href="/akademikx/public/lesson/quiz_detail/${test.id}" class="btn morumsu-btn me-1">
                        <i class="fa-regular fa-eye"></i>
                    </a>
                    <a href="#" onclick="deleteTest(${test.id})" class="btn kirmizi-btn">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        `).join('') || '<tr><td colspan="6" class="text-center">Kayıt bulunamadı.</td></tr>';
    }

    function renderPagination(totalPages) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = 'page-item' + (i === currentPage ? ' active' : '');
            li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            li.addEventListener('click', e => {
                e.preventDefault();
                currentPage = i;
                fetchTests();
            });
            pagination.appendChild(li);
        }
    }

    function deleteTest(testId) {
        if (!confirm('Bu testi silmek istediğinize emin misiniz?')) return;
        fetch(`/akademikx/public/api/tests/${testId}`, { method: 'DELETE' })
            .then(res => {
                if (!res.ok) throw new Error('Silme başarısız');
                return res.json();
            })
            .then(data => {
                alert(data.message || 'Test silindi.');
                fetchTests();
            })
            .catch(err => {
                console.error(err);
                alert('Bir hata oluştu: ' + err.message);
            });
    }

    function escapeHtml(text) {
        return text?.replace(/[&<>"'`=\/]/g, s => ({
            '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',
            "'":'&#39;','`':'&#x60;','=':'&#x3D;','/':'&#x2F;'
        })[s]) || '';
    }

    document.getElementById('limitSelect').addEventListener('change', () => { currentPage = 1; fetchTests(); });
    document.getElementById('searchInput').addEventListener('input', () => { currentPage = 1; fetchTests(); });
    fetchTests();
    </script>
</body>
</html>
