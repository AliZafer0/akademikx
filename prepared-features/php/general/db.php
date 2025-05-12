
<?php
//veri Tabanı bağlantısı

$host = 'localhost'; // sunucu adı
$dbname = 'akademikx_db'; // Veritabanı İsmi
$username = 'root';
$password = '';

try{
  $pdo = new PDO("mysql:host =$host;dbname=$dbname;charset=utf8",$username,$password);
  $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e)
{
    die("veri tabanı bağlantısı başarısız" . $e->getMessage());
}
