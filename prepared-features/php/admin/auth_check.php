<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Kullanıcı yoksa ya da admin değilse buraya girer
    header("location: ../login");
    exit;
}
?>