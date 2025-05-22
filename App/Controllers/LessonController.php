<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Courses;

class LessonController {
    private $courseModel;

    public function __construct() {
        session_start();
        $this->courseModel = new Courses();
    }

    public function lessonDetailsIndex($id) {
        $data = $this->courseModel->getCourseById($id);
        require_once __DIR__ . '/../Views/Courses/course_detail.php';
    }

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

    public function lessonMenuIndex($id) {
        $course = $this->courseModel->getCourseById($id);
        require_once __DIR__ . '/../Views/Courses/lesson-menu.php';
    }
}
