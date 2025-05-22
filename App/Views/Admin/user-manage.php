<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Admin Panel</title>
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

        <input class="table-search ms-3" type="search" id="searchInput" placeholder="Kullanıcı Ara..">

        <a href="#" class="btn header-yesil-btn ms-auto" data-bs-toggle="modal" data-bs-target="#addUserModal">Yeni Kullanıcı Ekle</a>

        <div class="dropdown">
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
                    <th>Kullanıcı Adı</th>
                    <th>Yetki</th>
                    <th>Kayıt Tarihi</th>
                    <th>Onay Durumu</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody id="userTableBody"></tbody>
        </table>

        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>

        <?php include __DIR__ . '/../partials/modals/user_edit_modal.php'; ?>
        <?php include __DIR__ . '/../partials/modals/user_add_modal.php'; ?>
    </div>
</div>
<?php include __DIR__ . '/../partials/nav_js.php'; ?>
<script>
let currentPage = 1;

function fetchUsers() {
    fetch(`/akademikx/public/api/admin/get-user?page=${currentPage}`)
        .then(res => res.json())
        .then(data => {
            renderUsers(data);
            renderPagination(1); // API’den total_pages yoksa 1 sayfa varsayılıyor
        });
}

function renderUsers(users) {
    const tbody = document.getElementById('userTableBody');
    tbody.innerHTML = '';
    users.forEach(user => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="checkbox" class="user-checkbox"></td>
            <td>${user.id}</td>
            <td>${user.username}</td>
            <td>${user.role}</td>
            <td>${user.created_at}</td>
            <td>${user.approved == '1' ? '<span class="statu-aktif">Onaylandı</span>' : '<span class="statu-pasif">Onaylanmadı</span>'}</td>
            <td>
                <a class="btn morumsu-btn" data-bs-toggle="modal" data-bs-target="#editUserModal" 
                   data-id="${user.id}" data-username="${user.username}" data-role="${user.role}" data-approve="${user.approved}">
                   <i class="fa-regular fa-eye"></i>
                </a>
                <a href="#" class="btn yesil-btn download-user"></a>
                <a href="#" onclick="deleteUser(${user.id})" class="btn kirmizi-btn">
                   <i class="fas fa-trash"></i>
                </a>
            </td>`;
        tbody.appendChild(tr);
    });
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
            fetchUsers();
        });
        pagination.appendChild(li);
    }
}

function deleteUser(userId) {
    if (confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')) {
        fetch(`/akademikx/public/api/users/${userId}`, {
            method: 'DELETE'
        })
        .then(res => {
            if (!res.ok) throw new Error("Silme başarısız");
            return res.json();
        })
        .then(data => {
            alert(data.message);
            fetchUsers();
        })
        .catch(err => alert("Bir hata oluştu: " + err.message));
    }
}

fetchUsers();
</script>

</body>
</html>