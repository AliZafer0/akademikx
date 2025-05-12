<?php
session_start();
// Veritabanı bağlantısı ve genel fonksiyonlar
require_once('../../../prepared-features/php/general/db.php');
require_once('../../../prepared-features/php/general/functions.php');
require_once("../../../prepared-features/php/admin/auth_check.php");
// Geri bildirim mesajı
$message = "";
$showModal = false;
// Sayfalama ve sıralama parametreleri
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
$sort_order = (isset($_GET['sort_order']) && $_GET['sort_order'] === 'desc') ? 'DESC' : 'ASC';
$offset = ($page - 1) * $limit;

// Toplam kullanıcı sayısı
$stmt_count = $pdo->prepare("SELECT COUNT(*) AS total FROM users");
$stmt_count->execute();
$total = $stmt_count->fetchColumn();
$total_pages = ceil($total / $limit);

// Sıralanabilir sütunları sınırla
$allowedSortColumns = ['id', 'username', 'role', 'created_at'];
if (!in_array($sort_by, $allowedSortColumns)) {
    $sort_by = 'id';
}

// Kullanıcıları getir
$sql = "SELECT * FROM users ORDER BY $sort_by $sort_order LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kullanıcı güncelleme işlemi
if (isset($_POST["username"]) && isset($_POST["role"])) {
    $editId = $_POST['id'];
    $editUsername = $_POST['username'];
    $editRole = $_POST['role'];
    $editApproved = isset($_POST['approved']) ? 1 : 0;

    $sql = "UPDATE users SET username = ?, role = ?, approved = ? WHERE id = ?";
    $updateStmt = $pdo->prepare($sql);
    $updateStmt->execute([$editUsername, $editRole, $editApproved, $editId]);
}

// Yeni Kullanıcı ekleme işlemi
if(isset($_POST['add_username']) && isset($_POST['add_role']) && isset($_POST['add_password']) && isset($_POST['add_approved']))
{
    $newUsername = $_POST['add_username'];
    $newRole = $_POST['add_role'];
    $newApproved = isset($_POST['add_password']) ? 1 : 0;
    $newpassword = password_hash($_POST["add_approved"], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (username,password_hash,approved,role) VALUES (?,?,?,?)";
    $stmt_add = $pdo->prepare($sql);
    $stmt_add->execute([$newUsername,$newpassword,$newApproved,$newRole]);
    $showModal = true;
}
?>

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
    <link href="../../../prepared-features/css/admin/nav_css.css" rel="stylesheet">
    <link href="../../../prepared-features/css/general/btn.css" rel="stylesheet">
    <link href="../../../prepared-features/css/general/pagination.css" rel="stylesheet">
</head>
<body>
<?php include_once "../../../prepared-features/php/admin/navbar.php"; ?>
<div class="page mt-4">
    <div class="table-header d-flex py-3 px-4 mb-2">
        <select class="form-select w-auto" onchange="updateFilters()">
            <option <?= $limit == 10 ? 'selected' : '' ?> value="10">10</option>
            <option <?= $limit == 20 ? 'selected' : '' ?> value="20">20</option>
            <option <?= $limit == 30 ? 'selected' : '' ?> value="30">30</option>
            <option <?= $limit == 40 ? 'selected' : '' ?> value="40">40</option>
            <option <?= $limit == 50 ? 'selected' : '' ?> value="50">50</option>
        </select>

        <form role="search">
            <input class="table-search" type="search" placeholder="Kullanıcı Ara.." aria-label="Search">
        </form>

        <a href="#" class="btn header-yesil-btn ms-auto" data-bs-toggle="modal" data-bs-target="#addUserModal">Yeni Kullanıcı Ekle</a>

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
                    <th scope="col"><a class="mx-3" href="?sort_by=id&sort_order=<?= $sort_order === 'ASC' ? 'desc' : 'ASC'; ?>">ID</a></th>
                    <th scope="col">Kullanıcı Adı</th>
                    <th scope="col">Yetki</th>
                    <th scope="col">Kayıt Tarihi</th>
                    <th scope="col">Onay Durumu</th>
                    <th scope="col">İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="checkbox-wrapper-42">
                                <input type="checkbox" id="checkbox-<?= $user['id']; ?>" class="custom-checkbox">
                                <label class="cbx" for="checkbox-<?= $user['id']; ?>"></label>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($user['id']); ?></td>
                        <td><?= htmlspecialchars($user['username']); ?></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                        <td><?= htmlspecialchars($user['created_at']); ?></td>
                        <td class="text-center">
                            <?php if ($user['approved']): ?>
                                <span class="statu-aktif">Onaylandı</span>
                            <?php else: ?>
                                <span class="statu-pasif">Onaylanmadı</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn morumsu-btn" data-bs-toggle="modal" data-bs-target="#editUserModal"
                               data-id="<?= $user['id'] ?>"
                               data-username="<?= htmlspecialchars($user['username']) ?>"
                               data-role="<?= htmlspecialchars($user['role']) ?>"
                               data-approve="<?= htmlspecialchars($user['approved']) ?>">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <a href="#" class="btn yesil-btn download-user"></a>
                            <a href="delete_user.php?id=<?= htmlspecialchars($user['id']); ?>" class="btn kirmizi-btn"
                               onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php include_once "../../../prepared-features/php/admin/pagination.php"; ?>
        <?php include_once "../../../prepared-features/php/admin/user_edit_modal.php"; ?>
            <?php include('../../../prepared-features/php/admin/user_add_modal.php')?>
<?php if ($showModal): ?>
  <script>
    window.addEventListener("DOMContentLoaded", function () {
      var successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    });

    function copyCredentials() {
      const username = document.getElementById("teacherUsername").innerText;
      const password = document.getElementById("teacherPassword").innerText;
      const textToCopy = `Kullanıcı Adı: ${username}\nŞifre: ${password}`;
      
      navigator.clipboard.writeText(textToCopy).then(() => {
        document.getElementById("copyStatus").innerText = "Bilgiler panoya kopyalandı!";
      }).catch(() => {
        document.getElementById("copyStatus").innerText = "Kopyalama başarısız oldu.";
      });
    }
  </script>
  <?php endif; ?>
    </div>
</div>
<?php include_once "../../../prepared-features/php/admin/nav_js.php"; ?>
</body>
</html>