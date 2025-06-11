<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\CourseContents;

class TestsController {

    private $model;

    /**
     * Oturumu başlatır ve CourseContents modelini yükler.
     *
     * @return void
     */
    public function __construct() {
        session_start();
        $this->model = new CourseContents();
    }

    /**
     * API: Yeni test oluşturur ve sorular ekleme sayfasına yönlendirir.
     *
     * @link POST /api/tests/create
     * @return void
     */
    public function createTest() {
        $course_id  = $_POST['course_id'];
        $title      = $_POST['title'];
        $duration   = $_POST['duration'] ?? null;
        $teacher_id = $_SESSION['user_id'];

        // 1. İçeriği oluştur
        $content_id = $this->model->createCourseContentForTest($course_id, $teacher_id, $title);

        // 2. Testi oluştur
        $test_id = $this->model->createTest($content_id, $title, $duration);

        // 3. Soru ekleme sayfasına yönlendir
        $redirectUrl = "/akademikx/public/teacher/questions/create?test_id={$test_id}&content_id={$content_id}";
        header("Location: $redirectUrl");
        exit;
    }

    /**
     * API: Mevcut testi günceller ve JSON yanıt döner.
     *
     * @link POST /api/tests/update
     * @return void
     */
    public function updateTest() {
        $test_id  = $_POST['test_id'];
        $title    = $_POST['title'];
        $duration = $_POST['duration'] ?? null;

        $this->model->updateTest($test_id, $title, $duration);
        $this->jsonResponse(['status' => 'updated']);
    }

    /**
     * API: Belirtilen testi siler ve JSON yanıt döner.
     *
     * @link POST /api/tests/delete
     * @return void
     */
    public function deleteTest() {
        $test_id = $_POST['test_id'];
        $this->model->deleteTest($test_id);
        $this->jsonResponse(['status' => 'deleted']);
    }

    /**
     * API: Teste yeni soru ekler ve uygun sayfaya yönlendirir.
     *
     * @link POST /api/questions/create
     * @return void
     */
    public function addQuestion() {
        $test_id = $_POST['test_id'];
        $text    = $_POST['question_text'];
        $type    = $_POST['type'];
        $points  = $_POST['points']   ?? 1;
        $order   = $_POST['order_no'] ?? 1;

        $question_id = $this->model->addQuestion($test_id, $text, $type, $points, $order);

        if ($type === 'multiple') {
            $redirect = "/akademikx/public/teacher/options/create?question_id={$question_id}&test_id={$test_id}";
        } else {
            $redirect = "/akademikx/public/teacher/questions/create?test_id={$test_id}&msg=soru_eklendi";
        }

        header("Location: $redirect");
        exit;
    }

    /**
     * API: Mevcut soruyu günceller ve JSON yanıt döner.
     *
     * @link POST /api/questions/update
     * @return void
     */
    public function updateQuestion() {
        $question_id = $_POST['question_id'];
        $text        = $_POST['question_text'];
        $points      = $_POST['points'];

        $this->model->updateQuestion($question_id, $text, $points);
        $this->jsonResponse(['status' => 'updated']);
    }

    /**
     * API: Belirtilen soruyu siler ve JSON yanıt döner.
     *
     * @link POST /api/questions/delete
     * @return void
     */
    public function deleteQuestion() {
        $question_id = $_POST['question_id'];
        $this->model->deleteQuestion($question_id);
        $this->jsonResponse(['status' => 'deleted']);
    }

    /**
     * Arama kriterine göre toplam test sayısını döner.
     *
     * @param string $search
     * @return int
     */
    public function countTests(string $search = ''): int {
        if ($search !== '') {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM tests 
                WHERE title LIKE :search
            ");
            $stmt->execute(['search' => "%{$search}%"]);
        } else {
            $stmt = $this->db->query("SELECT COUNT(*) FROM tests");
        }
        return (int)$stmt->fetchColumn();
    }

    /**
     * Sayfalanmış test listesini döner.
     *
     * @param int    $limit
     * @param int    $offset
     * @param string $search
     * @return array
     */
    public function getTests(int $limit, int $offset, string $search = ''): array {
        if ($search !== '') {
            $stmt = $this->db->prepare("
                SELECT id, title, duration, created_at 
                FROM tests
                WHERE title LIKE :search
                ORDER BY id DESC
                LIMIT :lim OFFSET :off
            ");
            $stmt->bindValue('search', "%{$search}%", PDO::PARAM_STR);
        } else {
            $stmt = $this->db->prepare("
                SELECT id, title, duration, created_at 
                FROM tests
                ORDER BY id DESC
                LIMIT :lim OFFSET :off
            ");
        }
        $stmt->bindValue('lim', $limit,  PDO::PARAM_INT);
        $stmt->bindValue('off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * API: Ders ID'sine göre testleri JSON olarak döner.
     *
     * @link GET /api/tests/by-lesson/{id}
     * @param int $id
     * @return void
     */
    public function getTestsByLesson($id): void {
        $lesson_id = $id;
        if ($lesson_id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'lesson_id parametresi gerekli']);
            exit;
        }

        $tests = $this->model->getTestsByCourseId($lesson_id);
        $this->jsonResponse($tests);
    }

    /**
     * API: Yeni seçenek ekler ve ilgili sayfaya yönlendirir.
     *
     * @link POST /api/options/create
     * @return void
     */
    public function addOption() {
        $question_id = $_POST['question_id'];
        $test_id     = $_POST['test_id']      ?? null;
        $text        = $_POST['option_text'];
        $is_correct  = $_POST['is_correct']   ?? false;

        try {
            $id  = $this->model->addOption($question_id, $text, (bool)$is_correct);
            $msg = "success";
        } catch (\PDOException $e) {
            $msg = "error";
        }

        $redirect = "/akademikx/public/teacher/options/create?question_id={$question_id}&test_id={$test_id}&msg={$msg}";
        header("Location: $redirect");
        exit;
    }

    /**
     * API: Belirtilen sorunun tüm seçeneklerini siler ve JSON yanıt döner.
     *
     * @link POST /api/options/delete-all
     * @return void
     */
    public function deleteOptionsByQuestion() {
        $question_id = $_POST['question_id'];
        $this->model->deleteOptionsByQuestion($question_id);
        $this->jsonResponse(['status' => 'deleted_all']);
    }

    /**
     * Verilen veriyi JSON olarak yanıtlar ve çıkış yapar.
     *
     * @param mixed $data
     * @return void
     */
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
