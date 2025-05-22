<?php
namespace App\Models;

use App\Core\Database;
use PDO;


class UploadModel
{
    private $uploadDir;

    public function __construct()
    {
        $this->uploadDir = dirname(__DIR__, 2) . '/public/uploads/';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
public function saveToDatabase(array $data): bool
{var_dump($data['course_id']); 
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=akademikx_db;charset=utf8mb4", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("
            INSERT INTO course_contents (course_id, teacher_id, type, title, description, file_url)
            VALUES (:course_id, :teacher_id, :type, :title, :description, :file_url)
        ");

        return $stmt->execute([
            ':course_id'   => $data['course_id'],
            ':teacher_id'  => $data['teacher_id'],
            ':type'        => $data['type'],
            ':title'       => $data['title'],
            ':description' => $data['description'],
            ':file_url'    => $data['file_url'],
        ]);
        
    } catch (PDOException $e) {
        throw new Exception('Veritabanı hatası: ' . $e->getMessage());
    }
}


    /**
     * Dosyayı kaydet ve yeni dosya adını döndür
     *
     * @param array $file $_FILES içindeki dosya verisi
     * @return string Kaydedilen dosya adı
     * @throws Exception
     */
public function uploadFile(array $file): string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new \Exception('Dosya yüklenirken hata oluştu.');
    }

    $originalName = basename($file['name']);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $mimeType = mime_content_type($file['tmp_name']);
    $fileType = $this->determineFileType($mimeType);

    $randomName = $this->generateRandomFilename($extension);
    $targetDir = $this->uploadDir . $fileType . '/';

    // Klasör yoksa oluştur
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $targetPath = $targetDir . $randomName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new \Exception('Dosya kaydedilemedi.');
    }

    // 🔥 Sadece dosya adını döndür (klasör olmadan)
    return $randomName;
}

private function determineFileType(string $mimeType): string
{
    if (str_starts_with($mimeType, 'video/')) {
        return 'videos';
    } elseif (str_starts_with($mimeType, 'image/')) {
        return 'images';
    } elseif (str_starts_with($mimeType, 'audio/')) {
        return 'audios';
    } elseif (str_starts_with($mimeType, 'application/pdf')) {
        return 'documents';
    }

    return 'others'; // bilinmeyenler için
}

private function generateRandomFilename(string $extension): string
{
    $timestamp = date('Ymd_His');
    $randomHash = bin2hex(random_bytes(5));
    return $timestamp . '_' . $randomHash . '_video.' . strtolower($extension);
}


}
