<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Teachers;
use App\Models\Enrollments;
use App\Models\CourseContents;
use App\Core\Auth;
use App\Models\Courses;

class TeacherController {
    private $teacherModel;
    private $coursesModel;
    private $coursesContents;
    private $Auth;
    private $Courses;

    /**
     * Sınıfı başlatır, oturumu açar ve gerekli modelleri yükler.
     *
     * @return void
     */
    public function __construct() {
        session_start();
        $this->teacherModel    = new Teachers();
        $this->coursesModel    = new Enrollments();
        $this->coursesContents = new CourseContents();
        $this->Auth            = new Auth();
        $this->Courses         = new Courses();
    }

    /**
     * Öğretmen paneli ana sayfasını görüntüler, giriş kontrolü yapar ve role göre yönlendirir.
     *
     * @return void
     */
    public function teacherPanelIndex() {
        $this->Auth->isLogin();
        if ($_SESSION['role'] == 'teacher' || $_SESSION['role'] == 'admin') {
            require_once __DIR__ . '/../Views/teacher/teacher-panel.php';
        } else {
            header('Location: /akademikx/public/');
            exit;
        }
    }

    /**
     * Test oluşturma sayfasını görüntüler; öğretmen yetkisi kontrolü yapar.
     *
     * @return void
     */
    public function createTestPage() {
        $this->Auth->isLogin();
        if ($_SESSION['role'] === 'teacher' || $_SESSION['role'] === 'admin') {
            require_once __DIR__ . '/../Views/tests/create_test.php';
        } else {
            header('Location: /akademikx/public/');
            exit;
        }
    }

    /**
     * Soru ekleme sayfasını görüntüler; öğretmen yetkisi kontrolü yapar.
     *
     * @return void
     */
    public function addQuestionPage() {
        $this->Auth->isLogin();
        if ($_SESSION['role'] === 'teacher' || $_SESSION['role'] === 'admin') {
            require_once __DIR__ . '/../Views/tests/add_question.php';
        } else {
            header('Location: /akademikx/public/');
            exit;
        }
    }

    /**
     * Seçenek ekleme sayfasını görüntüler; öğretmen yetkisi kontrolü yapar.
     *
     * @return void
     */
    public function addOptionPage() {
        $this->Auth->isLogin();
        if ($_SESSION['role'] === 'teacher' || $_SESSION['role'] === 'admin') {
            require_once __DIR__ . '/../Views/tests/add_option.php';
        } else {
            header('Location: /akademikx/public/');
            exit;
        }
    }

    /**
     * API: Belirtilen ID'ye ait test detayını JSON olarak döner.
     *
     * @link GET /api/tests/{id}
     * @param int $id
     * @return void
     */
    public function getDetail(int $id) {
        header('Content-Type: application/json; charset=utf-8');

        $test = $this->coursesContents->getByIdWithQuestions($id);
        if (!$test) {
            http_response_code(404);
            echo json_encode(['error' => 'Test bulunamadı']);
            return;
        }

        echo json_encode($test);
    }

    /**
     * API: Cevapları değerlendirir, kaydeder ve JSON olarak skoru döner.
     *
     * @link POST /api/tests/{id}/submit
     * @param int $id
     * @return void
     */
    public function submit(int $id) {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Giriş yapmanız gerekiyor']);
            return;
        }

        $data    = json_decode(file_get_contents('php://input'), true);
        $answers = $data['answers'] ?? [];

        $eval  = $this->coursesContents->evaluateTest($id, $answers);
        $score = $eval['score'];
        $max   = $eval['maxScore'];

        $this->coursesContents->saveTestAttempt($id, $userId, $score, $answers);

        echo json_encode([
            'score'    => $score,
            'maxScore' => $max
        ]);
    }

    /**
     * API: Öğrencinin bu testi daha önce çözüp çözmediğini JSON olarak döner.
     *
     * @link GET /api/tests/{id}/status
     * @param int $id
     * @return void
     */
    public function status(int $id) {
        header('Content-Type: application/json; charset=utf-8');

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Giriş yapmanız gerekiyor']);
            return;
        }

        $attempted = $this->coursesContents->userAttemptedTest($id, $userId);
        echo json_encode(['attempted' => (bool)$attempted]);
    }

    /**
     * Quiz detay sayfasını görüntüler veya çözüldüyse önceki sayfaya yönlendirir.
     *
     * @param int $id
     * @return void
     */
    public function detailView(int $id) {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /akademikx/public/login');
            exit;
        }

        if ($this->coursesContents->userAttemptedTest($id, $userId)) {
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/akademikx/public/'));
            exit;
        }

        include __DIR__ . '/../views/courses/quiz_detail.php';
    }

    /**
     * API: Öğretmenin kurslarını JSON olarak döner.
     *
     * @link GET /api/teacher-courses/{id}
     * @param int $id
     * @return void
     */
    public function getTeacherCourses($id) {
        header('Content-Type: application/json');
        echo json_encode($this->teacherModel->getTeacherCourses($id));
    }

    /**
     * API: Derse kayıtlı öğrenci sayısını JSON olarak döner.
     *
     * @link GET /api/student-count/{id}
     * @param int $id
     * @return void
     */
    public function getStudentCount($id) {
        $studentCount = $this->coursesModel->getCourseStudentCount($id);
        header('Content-Type: application/json');
        echo json_encode(['count' => $studentCount]);
    }

    /**
     * Öğretmen ders detay sayfasını görüntüler, yetki kontrolü yapar.
     *
     * @param int $id
     * @return void
     */
    public function teacherCourseDetailIndex($id) {
        $this->Auth->isLogin();
        if ($_SESSION['role'] == 'teacher' || $_SESSION['role'] == 'admin') {
            require_once __DIR__ . '/../Views/teacher/teacher-course-detail.php';
        } else {
            header('Location: /akademikx/public/');
            exit;
        }
    }

    /**
     * API: Derse yeni program kaydı ekler ve referer sayfasına yönlendirir.
     *
     * @link POST /api/add-schedule
     * @return void
     */
    public function teacherAddSchedule() {
        $day_of_week = $_POST['day_of_week'] ?? null;
        $start_time  = $_POST['start_time']  ?? null;
        $end_time    = $_POST['end_time']    ?? null;
        $course_id   = $_POST['lesson_id']   ?? null;

        $Add = $this->coursesContents->addSchedule($course_id, $day_of_week, $start_time, $end_time);

        if ($Add) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=1');
            exit;
        }
    }

    /**
     * API: Öğretmenin programını JSON olarak döner.
     *
     * @link GET /get-schedules-teachers/{id}
     * @param int $user_id
     * @return void
     */
    public function getsTeacherSchedule($user_id) {
        header('Content-Type: application/json');
        echo json_encode($this->Courses->getScheduleTeacher($user_id));
    }

    /**
     * Öğretmen haftalık program sayfasını görüntüler.
     *
     * @return void
     */
    public function TeacherScheduleIndex() {
        $this->Auth->isLogin();
        require_once __DIR__ . '/../Views/Teacher/weekly-schedule-teacher.php';
    }
}
