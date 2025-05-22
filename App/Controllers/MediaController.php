<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\CourseContents;

class MediaController {
    private $contentModel;

    public function __construct() {
        session_start();
        $this->contentModel = new CourseContents();
    }

    public function playVideoIndex($filename) {
        require_once __DIR__ . '/../Views/Courses/play-video.php';
    }

    public function getLessonContents($id) {
        header('Content-Type: application/json');
        echo json_encode($this->contentModel->getlessonContentsById($id));
    }

    public function getLessonSchedule($id) {
        header('Content-Type: application/json');
        echo json_encode($this->contentModel->getLessonScheduleById($id));
    }

    public function videoDetailsByFileName($filename) {
        header('Content-Type: application/json');
        echo json_encode($this->contentModel->fileDetails($filename));
    }

    public function viewImageIndex($filename) {
        require_once __DIR__ . '/../Views/Courses/view_lesson_image.php';
    }

    public function viewDocumentIndex($filename) {
        require_once __DIR__ . '/../Views/Courses/view_lesson_document.php';
    }
}
