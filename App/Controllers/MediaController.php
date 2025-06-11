<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\CourseContents;

class MediaController {
    private $contentModel;

    /**
     * Sınıfı başlatır, oturumu açar ve CourseContents modelini yükler.
     *
     * @return void
     */
    public function __construct() {
        session_start();
        $this->contentModel = new CourseContents();
    }

    /**
     * Video oynatma sayfasını yükler.
     *
     * @param string $filename Oynatılacak video dosya adı
     * @return void
     */
    public function playVideoIndex($filename) {
        require_once __DIR__ . '/../Views/Courses/play-video.php';
    }

    /**
     * Ders içeriklerini JSON formatında döner.
     *
     * @link GET /get-lesson-contents/{id}
     * @param int $id Ders (course) ID
     * @return void
     */
    public function getLessonContents($id) {
        header('Content-Type: application/json');
        echo json_encode($this->contentModel->getlessonContentsById($id));
    }

    /**
     * Ders programını JSON formatında döner.
     *
     * @link GET /get-lesson-schedule/{id}
     * @param int $id Ders (course) ID
     * @return void
     */
    public function getLessonSchedule($id) {
        header('Content-Type: application/json');
        echo json_encode($this->contentModel->getLessonScheduleById($id));
    }

    /**
     * Video dosyasına ilişkin detayları JSON formatında döner.
     *
     * @link GET /file-details/{filename}
     * @param string $filename Dosya adı
     * @return void
     */
    public function videoDetailsByFileName($filename) {
        header('Content-Type: application/json');
        echo json_encode($this->contentModel->fileDetails($filename));
    }

    /**
     * Ders görseli görüntüleme sayfasını yükler.
     *
     * @param string $filename Görüntülenecek dosya adı
     * @return void
     */
    public function viewImageIndex($filename) {
        require_once __DIR__ . '/../Views/Courses/view_lesson_image.php';
    }

    /**
     * Ders doküman görüntüleme sayfasını yükler.
     *
     * @param string $filename Görüntülenecek dosya adı
     * @return void
     */
    public function viewDocumentIndex($filename) {
        require_once __DIR__ . '/../Views/Courses/view_lesson_document.php';
    }
}
