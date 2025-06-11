<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\UploadModel;
use Exception;

class UploadController
{
    private $model;

    /**
     * Sınıfı başlatır, oturumu açar ve UploadModel’i yükler.
     *
     * @return void
     */
    public function __construct()
    {
        session_start();
        $this->model = new UploadModel();
    }

    /**
     * API: Medya dosyasını POST isteğiyle kabul eder, yükler, veritabanına kaydeder ve
     *       ardından referer sayfasına yönlendirir. Eksik veya hatalı parametrelerde
     *       geri bağlantıya hata mesajıyla döner.
     *
     * @link POST /api/add-media
     * @return void
     */
    public function addMedia()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?hata=Yalnızca POST isteği kabul edilir.');
            exit;
        }

        $requiredFields = ['type', 'title', 'description', 'lesson_id'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '?hata=Eksik+parametre:+'.$field);
                exit;
            }
        }

        $type = $_POST['type'];

        $fileInputName = match ($type) {
            'video'    => 'uploadVideo_file',
            'image'    => 'uploadImages_file',
            'document' => 'uploadDocuments_file',
            default    => null
        };

        if (!$fileInputName || !isset($_FILES[$fileInputName])) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?hata=Geçerli+bir+dosya+yükleme+türü+belirtilmedi+veya+dosya+eksik.');
            exit;
        }

        try {
            $filename = $this->model->uploadFile($_FILES[$fileInputName]);

            $data = [
                'course_id'   => $_POST['lesson_id'],
                'teacher_id'  => $_SESSION['user_id'],
                'type'        => $type,
                'title'       => $_POST['title'],
                'description' => $_POST['description'],
                'file_url'    => $filename // sadece dosya adı
            ];

            $this->model->saveToDatabase($data);

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;

        } catch (Exception $e) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?hata=' . urlencode('Hata: ' . $e->getMessage()));
            exit;
        }
    }
}
