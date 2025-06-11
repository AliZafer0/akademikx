<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class CourseContents
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Kurs içeriğini course_id parametresine göre getirir.
     *
     * @param int $id
     * @return array
     */
    public function getLessonContentsById($id)
    {
        $sql = "SELECT * FROM course_contents WHERE course_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Test için yeni kurs içeriği oluşturur.
     *
     * @param int $course_id
     * @param int $teacher_id
     * @param string $title
     * @return int
     */
    public function createCourseContentForTest(int $course_id, int $teacher_id, string $title): int
    {
        $sql = "INSERT INTO course_contents (course_id, teacher_id, title, type) 
                VALUES (:course_id, :teacher_id, :title, 'test')";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->execute();
        return (int) $this->db->lastInsertId();
    }

    /**
     * Test detayını soruları ve seçenekleriyle birlikte getirir.
     *
     * @param int $testId
     * @return array|null
     */
    public function getByIdWithQuestions(int $testId): ?array
    {
        $sql = "
            SELECT t.id, t.title, t.duration, cc.description
            FROM tests t
            LEFT JOIN course_contents cc ON t.content_id = cc.id
            WHERE t.id = :test_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':test_id', $testId, PDO::PARAM_INT);
        $stmt->execute();
        $test = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$test) {
            return null;
        }

        $sqlQ = "
            SELECT id AS question_id, question_text, type, points, order_no
            FROM test_questions
            WHERE test_id = :test_id
            ORDER BY order_no
        ";
        $stmtQ = $this->db->prepare($sqlQ);
        $stmtQ->bindParam(':test_id', $testId, PDO::PARAM_INT);
        $stmtQ->execute();
        $questions = $stmtQ->fetchAll(PDO::FETCH_ASSOC);

        foreach ($questions as &$q) {
            $question_id = $q['question_id'];
            $sqlO = "
                SELECT id AS option_id, option_text, is_correct
                FROM test_question_options
                WHERE question_id = :question_id
                ORDER BY id
            ";
            $stmtO = $this->db->prepare($sqlO);
            $stmtO->bindParam(':question_id', $question_id, PDO::PARAM_INT);
            $stmtO->execute();
            $q['options'] = $stmtO->fetchAll(PDO::FETCH_ASSOC);
        }

        $test['questions'] = $questions;
        return $test;
    }

    /**
     * Test cevaplarını değerlendirir ve toplam skoru hesaplar.
     *
     * @param int $testId
     * @param array $answers
     * @return array
     */
    public function evaluateTest(int $testId, array $answers): array
    {
        $test = $this->getByIdWithQuestions($testId);
        $score    = 0;
        $maxScore = 0;

        foreach ($test['questions'] as $q) {
            $qid    = $q['question_id'];
            $points = (int)$q['points'];
            $maxScore += $points;

            if (!isset($answers[$qid])) {
                continue;
            }
            $ans = $answers[$qid];

            if ($q['type'] === 'multiple') {
                foreach ($q['options'] as $opt) {
                    if ((int)$opt['is_correct'] === 1
                        && (string)$opt['option_id'] === (string)$ans
                    ) {
                        $score += $points;
                        break;
                    }
                }
            } else {
                if (trim($ans) !== '') {
                    $score += $points;
                }
            }
        }

        return ['score' => $score, 'maxScore' => $maxScore];
    }

    /**
     * Test girişini ve cevapları kaydeder.
     *
     * @param int $testId
     * @param int $userId
     * @param int $score
     * @param array $answers
     * @return int
     */
    public function saveTestAttempt(int $testId, int $userId, int $score, array $answers): int
    {
        $sql = "
            INSERT INTO user_test_attempts
              (user_id, test_id, started_at, completed_at, total_score)
            VALUES (:user_id, :test_id, NOW(), NOW(), :score)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':test_id', $testId, PDO::PARAM_INT);
        $stmt->bindParam(':score', $score, PDO::PARAM_INT);
        $stmt->execute();
        $attemptId = (int)$this->db->lastInsertId();

        foreach ($answers as $qid => $ans) {
            $sqlQ = "
                SELECT type, points
                FROM test_questions
                WHERE id = :question_id
            ";
            $stmtQ = $this->db->prepare($sqlQ);
            $stmtQ->bindParam(':question_id', $qid, PDO::PARAM_INT);
            $stmtQ->execute();
            $qData = $stmtQ->fetch(PDO::FETCH_ASSOC);

            $points        = (int)$qData['points'];
            $type          = $qData['type'];
            $isCorrect     = 0;
            $selectedOptId = null;
            $answerText    = null;
            $awarded       = 0;

            if ($type === 'multiple') {
                $selectedOptId = (int)$ans;
                $sqlO = "
                    SELECT is_correct
                    FROM test_question_options
                    WHERE id = :option_id
                ";
                $stmtO = $this->db->prepare($sqlO);
                $stmtO->bindParam(':option_id', $selectedOptId, PDO::PARAM_INT);
                $stmtO->execute();
                $opt = $stmtO->fetch(PDO::FETCH_ASSOC);
                if ($opt && (int)$opt['is_correct'] === 1) {
                    $isCorrect = 1;
                    $awarded   = $points;
                }
            } else {
                $answerText = $ans;
                if (trim($ans) !== '') {
                    $isCorrect = 1;
                    $awarded   = $points;
                }
            }

            $sqlA = "
                INSERT INTO user_answers
                  (attempt_id, question_id, selected_option_id, answer_text, is_correct, score)
                VALUES (:attempt_id, :question_id, :selected_option_id, :answer_text, :is_correct, :score)
            ";
            $stmtA = $this->db->prepare($sqlA);
            $stmtA->bindParam(':attempt_id', $attemptId, PDO::PARAM_INT);
            $stmtA->bindParam(':question_id', $qid, PDO::PARAM_INT);
            $stmtA->bindParam(':selected_option_id', $selectedOptId, PDO::PARAM_INT);
            $stmtA->bindParam(':answer_text', $answerText, PDO::PARAM_STR);
            $stmtA->bindParam(':is_correct', $isCorrect, PDO::PARAM_INT);
            $stmtA->bindParam(':score', $awarded, PDO::PARAM_INT);
            $stmtA->execute();
        }

        $sqlS = "
            INSERT INTO student_test_status (user_id, test_id)
            VALUES (:user_id, :test_id)
            ON DUPLICATE KEY UPDATE attempted_at = NOW()
        ";
        $stmtS = $this->db->prepare($sqlS);
        $stmtS->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmtS->bindParam(':test_id', $testId, PDO::PARAM_INT);
        $stmtS->execute();

        return $attemptId;
    }

    /**
     * Kullanıcının testi daha önce çözüp çözmediğini kontrol eder.
     *
     * @param int $testId
     * @param int $userId
     * @return bool
     */
    public function userAttemptedTest(int $testId, int $userId): bool
    {
        $sql = "
            SELECT COUNT(*)
            FROM student_test_status
            WHERE test_id = :test_id AND user_id = :user_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':test_id', $testId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Ders ID'sine göre testleri listeler.
     *
     * @param int $course_id
     * @return array
     */
    public function getTestsByCourseId(int $course_id): array
    {
        $sql = "
            SELECT
                t.id,
                t.content_id,
                t.title,
                t.duration,
                t.created_at
            FROM tests t
            JOIN course_contents cc ON cc.id = t.content_id
            WHERE cc.course_id = :course_id
            ORDER BY t.created_at DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Toplam test sayısını döner, arama varsa filtre uygular.
     *
     * @param string $search
     * @return int
     */
    public function countTests(string $search = ''): int
    {
        if ($search !== '') {
            $sql = "SELECT COUNT(*) FROM tests WHERE title LIKE :search";
            $stmt = $this->db->prepare($sql);
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
            $stmt->execute();
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM tests");
            $stmt->execute();
        }
        return (int)$stmt->fetchColumn();
    }

    /**
     * Sayfalanmış test listesini döner.
     *
     * @param int $limit
     * @param int $offset
     * @param string $search
     * @return array
     */
    public function getTests(int $limit, int $offset, string $search = ''): array
    {
        if ($search !== '') {
            $sql = "
                SELECT id, title, duration, created_at
                FROM tests
                WHERE title LIKE :search
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset
            ";
            $stmt = $this->db->prepare($sql);
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        } else {
            $sql = "
                SELECT id, title, duration, created_at
                FROM tests
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset
            ";
            $stmt = $this->db->prepare($sql);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ders programını course_id parametresine göre getirir.
     *
     * @param int $id
     * @return array
     */
    public function getLessonScheduleById($id)
    {
        $sql = "SELECT * FROM course_schedule WHERE course_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Verilen dosya adına göre içerik detaylarını getirir.
     *
     * @param string $filename
     * @return array
     */
    public function fileDetails($filename)
    {
        $sql = "SELECT * FROM course_contents WHERE file_url = :filename";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':filename', $filename, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ders programına yeni bir kayıt ekler.
     *
     * @param int $course_id
     * @param string $day_of_week
     * @param string $start_time
     * @param string $end_time
     * @return bool
     */
    public function addSchedule($course_id, $day_of_week, $start_time, $end_time)
    {
        $sql = "INSERT INTO course_schedule (course_id, day_of_week, start_time, end_time)
                VALUES (:course_id, :day_of_week, :start_time, :end_time)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':day_of_week', $day_of_week, PDO::PARAM_STR);
        $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Yeni test oluşturur.
     *
     * @param int $content_id
     * @param string $title
     * @param int|null $duration
     * @return int
     */
    public function createTest(int $content_id, string $title, ?int $duration = null): int
    {
        $sql = "INSERT INTO tests (content_id, title, duration) VALUES (:content_id, :title, :duration)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':content_id', $content_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':duration', $duration, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $this->db->lastInsertId();
    }

    /**
     * Mevcut testi günceller.
     *
     * @param int $test_id
     * @param string $title
     * @param int|null $duration
     * @return void
     */
    public function updateTest(int $test_id, string $title, ?int $duration = null): void
    {
        $sql = "UPDATE tests SET title = :title, duration = :duration WHERE id = :test_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':duration', $duration, PDO::PARAM_INT);
        $stmt->bindParam(':test_id', $test_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Testi siler.
     *
     * @param int $test_id
     * @return void
     */
    public function deleteTest(int $test_id): void
    {
        $sql = "DELETE FROM tests WHERE id = :test_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':test_id', $test_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Yeni soru ekler.
     *
     * @param int $test_id
     * @param string $question_text
     * @param string $type
     * @param int $points
     * @param int $order_no
     * @return int
     */
    public function addQuestion(int $test_id, string $question_text, string $type, int $points = 1, int $order_no = 1): int
    {
        $sql = "INSERT INTO test_questions (test_id, question_text, type, points, order_no)
                VALUES (:test_id, :question_text, :type, :points, :order_no)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':test_id', $test_id, PDO::PARAM_INT);
        $stmt->bindParam(':question_text', $question_text, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':points', $points, PDO::PARAM_INT);
        $stmt->bindParam(':order_no', $order_no, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $this->db->lastInsertId();
    }

    /**
     * Mevcut soruyu günceller.
     *
     * @param int $question_id
     * @param string $question_text
     * @param int $points
     * @return void
     */
    public function updateQuestion(int $question_id, string $question_text, int $points): void
    {
        $sql = "UPDATE test_questions SET question_text = :text, points = :points WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':text', $question_text, PDO::PARAM_STR);
        $stmt->bindParam(':points', $points, PDO::PARAM_INT);
        $stmt->bindParam(':id', $question_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Soruyu siler.
     *
     * @param int $question_id
     * @return void
     */
    public function deleteQuestion(int $question_id): void
    {
        $sql = "DELETE FROM test_questions WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $question_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Yeni seçenek ekler.
     *
     * @param int $question_id
     * @param string $text
     * @param bool $is_correct
     * @return int
     */
    public function addOption(int $question_id, string $text, bool $is_correct): int
    {
        $sql = "INSERT INTO test_question_options (question_id, option_text, is_correct)
                VALUES (:question_id, :text, :is_correct)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->bindParam(':text', $text, PDO::PARAM_STR);
        $stmt->bindParam(':is_correct', $is_correct, PDO::PARAM_BOOL);
        $stmt->execute();
        return (int) $this->db->lastInsertId();
    }

    /**
     * Sorunun tüm seçeneklerini siler.
     *
     * @param int $question_id
     * @return void
     */
    public function deleteOptionsByQuestion(int $question_id): void
    {
        $sql = "DELETE FROM test_question_options WHERE question_id = :qid";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':qid', $question_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Çoktan seçmeli cevapları otomatik olarak puanlar ve toplam skoru döner.
     *
     * @param int $attempt_id
     * @return float
     */
    public function autoScoreAttempt(int $attempt_id): float
    {
        $sql = "
            SELECT ua.id, q.id AS question_id, q.points, ua.selected_option_id, o.id AS correct_option_id
            FROM user_answers ua
            JOIN test_questions q ON ua.question_id = q.id
            LEFT JOIN test_question_options o ON o.question_id = q.id AND o.is_correct = 1
            WHERE ua.attempt_id = :attempt_id AND q.type = 'multiple'
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':attempt_id', $attempt_id, PDO::PARAM_INT);
        $stmt->execute();
        $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = 0;
        foreach ($answers as $a) {
            $is_correct = ($a['selected_option_id'] == $a['correct_option_id']) ? 1 : 0;
            $score = $is_correct ? $a['points'] : 0;

            $update = $this->db->prepare("UPDATE user_answers SET is_correct = :ic, score = :sc WHERE id = :id");
            $update->bindParam(':ic', $is_correct, PDO::PARAM_BOOL);
            $update->bindParam(':sc', $score, PDO::PARAM_INT);
            $update->bindParam(':id', $a['id'], PDO::PARAM_INT);
            $update->execute();

            $total += $score;
        }

        return $total;
    }

    /**
     * Test sorularını ve seçeneklerini yapısal olarak getirir.
     *
     * @param int $test_id
     * @return array
     */
    public function getFullTestStructure(int $test_id): array
    {
        $questions = $this->getQuestionsByTestId($test_id);
        foreach ($questions as &$q) {
            if ($q['type'] === 'multiple') {
                $q['options'] = $this->getOptionsByQuestionId($q['id']);
            }
        }
        return $questions;
    }

    /**
     * Teste ait soruları getirir.
     *
     * @param int $test_id
     * @return array
     */
    public function getQuestionsByTestId(int $test_id): array
    {
        $sql = "SELECT * FROM test_questions WHERE test_id = :id ORDER BY order_no";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $test_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sorunun seçeneklerini getirir.
     *
     * @param int $question_id
     * @return array
     */
    public function getOptionsByQuestionId(int $question_id): array
    {
        $sql = "SELECT * FROM test_question_options WHERE question_id = :qid";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':qid', $question_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
