<?php
    session_start();
    require_once("prepared-features/php/general/functions.php");
    require_once("prepared-features/php/general/db.php");

    $sql = "SELECT * FROM courses";
    $stmt_courses = $pdo->prepare($sql);
    $stmt_courses->execute();
    $courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | Anasayfa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .course-card {
         transition: transform 0.3s, box-shadow 0.3s;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .card-img-top {
            height: 180px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include_once('prepared-features/php/general/navbar.php') ?>
    <div class="container py-5">
        <div class="row g-4">
            <?php foreach($courses as $course):?>
                <div class="col-md-4">
                    <div class="card course-card h-100">
                        <img src="media/images/attachments/general-lesson-image.jpg" class="card-img-top" alt="Ders 1">
                        <div class="card-body">
                            <h5 class="card-title"><?=htmlspecialchars($course['title'])?></h5>
                            <p class="card-text"><?=htmlspecialchars($course['description'])?></p>
                        </div>
                            <div class="card-footer bg-white border-0">
                            <a href="lesson/lesson_details.php?lesson_id=<?=htmlspecialchars($course['id'])?>" class="btn btn-outline-primary w-100">Derse Git</a>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>