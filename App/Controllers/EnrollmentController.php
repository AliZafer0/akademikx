<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Enrollments;
use App\Core\Auth;



class EnrollmentController {
    private $enrollmentModel;
    private $Auth;

    public function __construct() {
        session_start();
        $this->enrollmentModel = new Enrollments();
        $this->Auth = new Auth();
    }

    public function checkEnrollmentStatus($studentId, $lessonId) {
        $isEnrolled = $this->enrollmentModel->isStudentEnrolled($studentId, $lessonId);
        echo json_encode(['isEnrolled' => $isEnrolled]);
    }

    public function addEnrollments() {
        $user_id = $_POST['user_id'] ?? null;
        $lesson_id = $_POST['lesson_id'] ?? null;

        if (!$user_id || !$lesson_id) {
            echo json_encode(['success' => false, 'message' => 'Eksik veri.']);
            return;
        }

        $status = $this->enrollmentModel->addEnrollment($user_id, $lesson_id);
        if ($status) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            $link = $_SERVER['HTTP_REFERER'];
            $separator = (parse_url($link, PHP_URL_QUERY)) ? '&' : '?';
            $redirectUrl = $link . $separator . 'error=1';
            header("Location: $redirectUrl");
            exit;
        }
    }

    public function myLessonsIndex() {
        $this->Auth->isLogin();
        require_once __DIR__ . '/../Views/Courses/my-lessons.php';
    }

    public function getEnrolledCourses($id) {
        header('Content-Type: application/json');
        echo json_encode($this->enrollmentModel->getCoursesByUserId($id));
    }
}
