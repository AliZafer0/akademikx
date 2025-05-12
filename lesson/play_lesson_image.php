<?php
    session_start();
    require_once('../prepared-features/php/general/db.php');
    require_once('../prepared-features/php/general/functions.php');

    if(isset($_SESSION['user_id']) && isset($_GET['image_name']))
    {
      if(courseElm($pdo,documentUrlToCourse($pdo,$_GET['image_name']),$_SESSION['user_id']) == false)
      {
        header('location: ../');
        exit;
      }

    }
    else
    {
      header('location: ../');
      exit;
    }

    $image_name = $_GET['image_name'];
    $image_url = '../media/images/' . $image_name;
    
    $sql = "SELECT * FROM course_contents WHERE file_url = '$image_name'";
    $stmt_image = $pdo->prepare($sql);
    $stmt_image->execute();
    $image = $stmt_image->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | <?=htmlspecialchars($image['title'])?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh; /* Tam ekran yüksekliği */
        }

        .image-box {
            width: 85%; /* image alanı ekranın %85'i kadar genişlik */
            max-width: 2200px; /* En fazla 1200px genişlik */
            height: 70vh; /* image alanının yüksekliği ekranın %90'ı kadar */
            display: flex;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
        }

        .image-box .image {
            width: 60%; /* image alanı sol tarafta geniş olacak */
            height: 100%;
            background-color: #000;
        }

        .image-box .description {
            width: 40%; /* Açıklama alanı sağda daha küçük */
            padding: 20px;
            background-color: #f7f7f7;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .description h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
        }

        .description p {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .description .btn {
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .description .btn:hover {
            background-color: #0056b3;
        }
        
    </style>

</head>
<body>
    <?php include_once('../prepared-features/php/general/navbar.php') ?>
    <div class="container mt-5">
        <div class="container">
            <div class="image-container">
                <div class="image-box">
                    <div class="image">
                         <img id="my-thumb" src="<?= htmlspecialchars($image_url) ?>">
                    </div>
                    <div class="description">
                        <h2><?=htmlspecialchars($image['title'])?></h2>
                        <p><?=htmlspecialchars($image['description'])?></p>
                    </div>
                </div>
            </div>
        </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>