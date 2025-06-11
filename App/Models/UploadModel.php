<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class UploadModel
{
    private $uploadDir;

    /**
     * UploadModel sınıfını başlatır ve yükleme dizinini oluşturur.
     *
     * @return void
     */
    public function __construct()
    {
        $this->uploadDir = dirname(__DIR__, 2) . '/public/uploads/';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Veritabanına dosya bilgilerini kaydeder.
     *
     * @param array $data ['course_id' => int, 'teacher_id' => int, 'type' => string, 'title' => string, 'description' => string, 'file_url' => string]
     * @return bool
     * @throws Exception
     */
    public function saveToDatabase(array $data): bool
    {
        var_dump($data['course_id']);
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=akademikx_db;charset=utf8mb4", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("
                INSERT INTO course_contents (course_id, teacher_id, type, title, description, file_url)
                VALUES (:course_id, :teacher_id, :type, :title, :description, :file_url)
            ");
            $stmt->bindParam(':course_id',   $data['course_id'],   PDO::PARAM_INT);
            $stmt->bindParam(':teacher_id',  $data['teacher_id'],  PDO::PARAM_INT);
            $stmt->bindParam(':type',        $data['type'],        PDO::PARAM_STR);
            $stmt->bindParam(':title',       $data['title'],       PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':file_url',    $data['file_url'],    PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception('Veritabanı hatası: ' . $e->getMessage());
        }
    }

    /**
     * Dosyayı diske kaydeder ve yeni dosya adını döndürür.
     *
     * @param array $file $_FILES formatında dosya verisi
     * @return string Kaydedilen dosya adı
     * @throws Exception
     */
    public function uploadFile(array $file): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Dosya yüklenirken hata oluştu.');
        }

        $originalName = basename($file['name']);
        $extension    = pathinfo($originalName, PATHINFO_EXTENSION);
        $mimeType     = mime_content_type($file['tmp_name']);
        $fileType     = $this->determineFileType($mimeType);

        $randomName = $this->generateRandomFilename($extension);
        $targetDir  = $this->uploadDir . $fileType . '/';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetPath = $targetDir . $randomName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Dosya kaydedilemedi.');
        }

        return $randomName;
    }

    /**
     * MIME tipine göre dosya tipini belirler.
     *
     * @param string $mimeType
     * @return string 'videos', 'images', 'audios', 'documents' veya 'others'
     */
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

        return 'others';
    }

    /**
     * Rastgele ve benzersiz bir dosya adı oluşturur.
     *
     * @param string $extension Dosya uzantısı
     * @return string Yeni dosya adı
     */
    private function generateRandomFilename(string $extension): string
    {
        $timestamp  = date('Ymd_His');
        $randomHash = bin2hex(random_bytes(5));
        return $timestamp . '_' . $randomHash . '_video.' . strtolower($extension);
    }
}
