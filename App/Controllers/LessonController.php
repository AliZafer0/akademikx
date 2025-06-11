<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Courses;

class LessonController {
    private $courseModel;

    /**
     * Sınıfı başlatır, oturumu açar ve Courses modelini yükler.
     *
     * @return void
     */
    public function __construct() {
        session_start();
        $this->courseModel = new Courses();
    }

    /**
     * Belirtilen dersin detay sayfasını görüntüler.
     *
     * @param int $id Ders ID'si
     * @return void
     */
    public function lessonDetailsIndex($id) {
        $data = $this->courseModel->getCourseById($id);
        require_once __DIR__ . '/../Views/Courses/course_detail.php';
    }

    /**
     * API: Belirtilen ID'ye ait ders detayını JSON olarak döner.
     *
     * @link GET /course-detail-json/{id}
     * @param int $id Ders ID'si
     * @return void
     */
    public function lessonDetails($id) {
        $course = $this->courseModel->getCourseById($id);
        if (!$course) {
            http_response_code(404);
            echo json_encode(['error' => 'Course not found']);
            return;
        }
        header('Content-Type: application/json');
        echo json_encode($course);
    }

    /**
     * Ders menü sayfasını görüntüler.
     *
     * @param int $id Ders ID'si
     * @return void
     */
    public function lessonMenuIndex($id) {
        $course = $this->courseModel->getCourseById($id);
        require_once __DIR__ . '/../Views/Courses/lesson-menu.php';
    }
}
