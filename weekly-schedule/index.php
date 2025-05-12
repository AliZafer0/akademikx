<?php
    session_start();
    require_once("../prepared-features/php/general/functions.php");
    require_once("../prepared-features/php/general/db.php");
    
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'student' && $_SESSION['role'] != 'admin'))
    {
        header('location: ../');
        exit;
    }
    
    // $pdo hazır varsayalım
    $student_id = $_SESSION['user_id'];

    $sql = "SELECT 
                c.id AS course_id, 
                c.title, 
                cs.day_of_week, 
                cs.start_time, 
                cs.end_time
            FROM enrollments e
            JOIN courses c ON e.course_id = c.id
            JOIN course_schedule cs ON cs.course_id = c.id
            WHERE e.student_id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user_id']]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $table = [];
    $weekdays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    $times = [];

// Verileri işleyip tabloya dönüştür
foreach ($rows as $row) {
    // Saat etiketini ayıkla (örnek: "10:00 - 15:00")
    $start = substr($row['start_time'], 0, 5);
    $end = substr($row['end_time'], 0, 5);
    $timeLabel = "$start - $end";

    // Zaman dilimini listeye ekle
    if (!in_array($timeLabel, $times)) {
        $times[] = $timeLabel;
    }

    // Matris sistemine uygun yerleştirme
    // [saat dilimi][gün] = ['title' => ..., 'id' => ...]
    $table[$timeLabel][$row['day_of_week']] = [
        'title' => $row['title'],
        'id' => $row['course_id']
    ];
}

// Zaman sırasına göre saatleri sırala
sort($times);

// Başlıklarda göstereceğimiz günleri de alalım
$schedule = $weekdays; // hepsi gözüksün


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
        .schedule-table {
            text-align: center;
            font-size: 14px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .schedule-table th,
        .schedule-table td {
            vertical-align: middle !important;
        }

        .lesson-card {
            background: linear-gradient(135deg, #90caf9, #42a5f5);
            color: white;
            padding: 10px 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .lesson-card:hover {
            background: linear-gradient(135deg, #42a5f5, #1e88e5);
            text-decoration: none;
            transform: scale(1.05);
        }

        .table-primary {
            background-color: #e3f2fd !important;
        }

        .table-secondary {
            background-color: #f1f3f4 !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include_once('../prepared-features/php/general/navbar.php') ?>
    <div class="conteiner">
        <table class="table table-bordered schedule-table mt-4">
            <thead class="table-primary">
                <tr>
                <th style="width: 120px;">Saat</th>
                <?php foreach ($schedule as $day): ?>
                    <th><?= htmlspecialchars(translateDay($day)) ?></th>
                <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($times as $time): ?>
                <tr>
                    <th class="table-secondary"><?= htmlspecialchars($time) ?></th>
                    <?php foreach ($weekdays as $day): ?>
                    <td>
                        <?php if (isset($table[$time][$day])): ?>
                        <?php
                            $lesson_title = htmlspecialchars($table[$time][$day]['title']);
                            $lesson_id = $table[$time][$day]['id']; // burada ID varsa
                        ?>
                             <a href="../my-lessons/lesson_menu.php?lesson_id=<?= $lesson_id ?>" class="lesson-card"><?= $lesson_title ?></a>
                        <?php endif; ?>
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>