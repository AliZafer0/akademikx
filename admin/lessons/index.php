<?php
    session_start();
    require_once("../../prepared-features/php/general/db.php");
    require_once("../../prepared-features/php/general/functions.php");
    require_once("../../prepared-features/php/admin/auth_check.php");
    $message = "";

    $limit = isset($_GET['limit']) ? $intval($_GET['limlit']) : 10;
    $page = isset($_GET['page']) ? max(1,intval($_GET['page'])) : 1;
    $short_by = isset($_GET['short_by']) ?  $_GET['short_by'] : 'id';
    $short_order = (isset($_GET['short_order']) && $_GET['short_order'] === 'desc') ? 'DESC' : 'ASC';
    $offset = ($page - 1) * $limit;
    
    $stmt_count = $pdo->prepare("SELECT COUNT(*) AS total FROM courses");
    $stmt_count->execute();
    $total = $stmt_count->fetchColumn();
    $total_pages = ceil($total / $limit);

    $sql = "SELECT * FROM courses ORDER BY $short_by $short_order LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit',$limit,PDO::PARAM_INT);
    $stmt->bindValue(':offset',$offset,PDO::PARAM_INT);
    $stmt->execute();
    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    $sql = "SELECT * FROM users WHERE role='teacher'";
    $stmt_teacher = $pdo->prepare($sql);
    $stmt_teacher->execute();
    $teachers = $stmt_teacher->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM categories";
    $stmt_categories = $pdo->prepare($sql);
    $stmt_categories->execute();
    $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST['id']) && isset($_POST['title']) && isset($_POST['desc']) && isset($_POST['teacher']) && isset($_POST['categories']))
    {
         $editID = $_POST['id'];
         $editTitle = $_POST['title'];
         $editDesc = $_POST['desc'];
         $editTeacher = $_POST['teacher'];
         $editCategories = $_POST['categories'];

         $update_stmt = $pdo->prepare("UPDATE courses SET title = ?, description = ?, teacher_id = ?, category_id = ?");
         $update_stmt->execute([$editTitle,$editDesc,$editTeacher,$editCategories]);
        

    };
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Admin Panel</title>
        <!-- Bootstrap 5.3-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- font awesome  -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="../../prepared-features/css/admin/nav_css.css" rel="stylesheet">
  <link rel="stylesheet" href="../../prepared-features/css/general/btn.css">

  <style>

</style>

</head>
<body>
    <?php include_once "../../prepared-features/php/admin/navbar.php"; ?>
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
                <input class="table-search" type="search" placeholder="Ders Ara.." aria-label="Search">
            </form>

            <a href="add_lessons/" class="btn header-yesil-btn ms-auto" >Yeni Ders Ekle</a>

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
                        <th scope="col">Başlık</th>
                        <th scope="col">Açıklama</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Öğretmen</th>
                        <th scope="col">Oluşturma Tarihi</th>
                        <th scope="col">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lessons as $lesson): ?>
                        <tr>
                            <td>
                                <div class="checkbox-wrapper-42">
                                    <input type="checkbox" id="checkbox-<?= $lesson['id']; ?>" class="custom-checkbox">
                                    <label class="cbx" for="checkbox-<?= $lesson['id']; ?>"></label>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($lesson['id']); ?></td>
                            <td><?= htmlspecialchars($lesson['title']); ?></td>
                            <td><?= htmlspecialchars(truncate_description($lesson['description'])); ?></td>
                            <td><?= htmlspecialchars($lesson['category_id']); ?></td>
                            <td><?= htmlspecialchars(idTeacherUsername($pdo,$lesson['teacher_id'])); ?></td>
                            <td><?= htmlspecialchars($lesson['created_at'])?></td>
                            <td>
                                <a class="btn morumsu-btn" data-bs-toggle="modal" data-bs-target="#editLessonModal"
                                data-id="<?= $lesson['id'] ?>"
                                data-title="<?= htmlspecialchars($lesson['title']) ?>"
                                data-desc="<?= htmlspecialchars($lesson['description']) ?>"
                                data-category="<?= htmlspecialchars($lesson['category_id']) ?>"
                                data-teacher="<? htmlspecialchars($pdo,idTeacherUsername($lesson['teacher_id']))?>"
                                data-created="<? htmlspecialchars($lesson['created_at'])?>">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="#" class="btn yesil-btn download-user"></a>
                                <a href="delete_user.php?id=<?= htmlspecialchars($lesson['id']); ?>" class="btn kirmizi-btn"
                                onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php include_once "../../prepared-features/php/admin/pagination.php"; ?>
            <?php include_once "../../prepared-features/php/admin/lesson_edit_modal.php"; ?>
        </div>
    </div>
    <?php include_once "../../prepared-features/php/admin/nav_js.php"; ?>
</body>
</html>