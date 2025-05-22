<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>401 | AkademikX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/icon.png" type="image/png">
    <style>
        /* Özel stiller */
        .custom-bg {
            position: relative;
            background-image: url('/akademikx/public/assets/images/404.png');
            background-size: cover;
            background-position: center;
        }
        .blur-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(5px); /* Arka plan resmine blur efekti uygular */
            -webkit-backdrop-filter: blur(5px); /* Webkit tabanlı tarayıcılarda da çalışması için */
        }
        .text-layer {
            position: relative;
            z-index: 1; /* Yazıları arka plan resminin üstüne getirir */
        }
    </style>
</head>

<!-- HOP HEMŞERİM BURDA NE İŞİN VAR? -->
<body class="custom-bg">
    <div class="blur-layer"></div> 
    <div class="text-layer d-flex align-items-center justify-content-center vh-100">
        <div class="text-center text-white">
            <h1 class="display-1 fw-bold">401</h1>
            <p class="fs-3"> Aradığınız Sayfaya Erişim Yetkiniz Yok</p>
            <p class="lead">
                Bu Sayfaya yetkiniz bulunmadığı için erişemiyorsunuz. Erişim izni için yöneticiyle iletişime geçiniz..
            </p>
            <a href="/akademikx/public/" class="btn btn-light">Ana Sayfaya Git</a>
        </div>
    </div>
</body>


</html>
