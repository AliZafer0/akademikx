<?php
    session_start();
    require_once('../prepared-features/php/general/db.php');
    require_once('../prepared-features/php/general/functions.php');

    if(isset($_SESSION['user_id']) && isset($_GET['document_name']))
    {
      if(courseElm($pdo,documentUrlToCourse($pdo,$_GET['document_name']),$_SESSION['user_id']) == false)
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


    $document_name = $_GET['document_name'];
    $document_url = '../media/documents/' . $document_name;
    
    $sql = "SELECT * FROM course_contents WHERE file_url = '$document_name'";
    $stmt_document = $pdo->prepare($sql);
    $stmt_document->execute();
    $document = $stmt_document->fetch(PDO::FETCH_ASSOC);

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkademikX | <?=htmlspecialchars($document['title'])?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    
        
    </style>

</head>
<body>
    <?php include_once('../prepared-features/php/general/navbar.php') ?>
    <div class="container mt-5">
        <div class="container">
            <div class="document-container">
                <div class="document-box">
                    <div class="document">
                         <embed src="<?= htmlspecialchars($document_url) ?>" type="application/pdf" width="100%" height="600px">
                    </div>
                    <div class="description">
                        <h2><?=htmlspecialchars($document['title'])?></h2>
                        <p><?=htmlspecialchars($document['description'])?></p>
                    </div>
                </div>
            </div>
        </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>