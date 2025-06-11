<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Enrollments;
use App\Core\Auth;

class EnrollmentController {
    private $enrollmentModel;
    private $Auth;

    /**
     * Sınıfı başlatır, oturumu açar ve Enrollment modelini yükler.
     *
     * @return void
     */
    public function __construct() {
        session_start();
        $this->enrollmentModel = new Enrollments();
        $this->Auth            = new Auth();
    }

    /**
     * API: Öğrencinin derse kayıtlı olup olmadığını JSON formatında döner.
     *
     * @link GET /Student-Enrolled/{studentId}/{lessonId}
     * @param int $studentId
     * @param int $lessonId
     * @return void
     */
    public function checkEnrollmentStatus($studentId, $lessonId) {
        $isEnrolled = $this->enrollmentModel->isStudentEnrolled($studentId, $lessonId);
        echo json_encode(['isEnrolled' => $isEnrolled]);
    }

    /**
     * API: Öğrenciyi derse kaydeder; işlem sonrası referer sayfasına yönlendirir.
     *
     * @link POST /lesson/addEnrollment
     * @return void
     */
    public function addEnrollments() {
        $user_id   = $_POST['user_id']   ?? null;
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
            $link      = $_SERVER['HTTP_REFERER'];
            $sep       = parse_url($link, PHP_URL_QUERY) ? '&' : '?';
            $redirect  = $link . $sep . 'error=1';
            header("Location: $redirect");
            exit;
        }
    }

    /**
     * Öğrencinin derslerini görüntüleyen sayfayı yükler.
     *
     * @link GET /my-lessons
     * @return void
     */
    public function myLessonsIndex() {
        $this->Auth->isLogin();
        require_once __DIR__ . '/../Views/Courses/my-lessons.php';
    }

    /**
     * API: Kullanıcının kayıtlı olduğu kursları JSON olarak döner.
     *
     * @link GET /Get-Enrolled-User/{id}
     * @param int $id
     * @return void
     */
    public function getEnrolledCourses($id) {
        header('Content-Type: application/json');
        echo json_encode($this->enrollmentModel->getCoursesByUserId($id));
    }
}
