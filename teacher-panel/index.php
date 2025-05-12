<?php
    session_start();
    require_once('../prepared-features/php/general/db.php');
    require_once('../prepared-features/php/general/functions.php');

    if(!$_SESSION['role'] == 'teacher' || !$_SESSION['role'] == 'admin')
    {
        header('location: ../');
        exit;
    }

    $teacher_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM courses WHERE teacher_id = $teacher_id";
    $stmt_courses = $pdo->prepare($sql);
    $stmt_courses->execute();
    $courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT COUNT(*) AS count FROM courses WHERE teacher_id = $teacher_id";
    $stmt_courses_count = $pdo->query($sql);
    $courses_count = $stmt_courses_count->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Öğretmen Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card-hover:hover {
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include_once('../prepared-features/php/general/navbar.php') ?>
        <div class="container py-4">
        <h2 class="mb-4">Merhaba Öğretmenim 👨‍🏫</h2>

        <div class="row g-4">
            <!-- Toplam Öğrenci Sayısı -->
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Toplam Öğrenci</h5>
                        <p class="card-text display-6">142</p>
                    </div>
                </div>
            </div>

            <!-- En Yakın Ders -->
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">En Yakın Ders</h5>
                        <p class="card-text">Alt Tarafa Taşındık<br><small>Şaka Şaka Battık Biz</small></p>
                    </div>
                </div>
            </div>

            <!-- Toplam Sorumluluk Ders -->
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Ders Sayısı</h5>
                        <p class="card-text display-6"><?=htmlspecialchars($courses_count['count'])?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ders Kartları -->
        <div class="mt-5">
            <h4>Sorumlu Olduğunuz Dersler</h4>
            <div class="row row-cols-1 row-cols-md-3 g-4 mt-2">
                <?php foreach($courses as $course):?>
                    <div class="col">
                        <div class="card card-hover h-100" onclick="window.location.href='course-detail.php?id=<?=htmlspecialchars($course['id'])?>'">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($course['title'])?></h5>
                                <p class="card-text"><?= htmlspecialchars(getStudentCountByCourseId($pdo,$course['id']))?></p>
                                <p class="card-text">Son düzenleme: 2 gün önce</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
                <!-- Daha fazla ders burada listelenebilir -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>