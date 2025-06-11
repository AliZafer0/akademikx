<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Courses
{
    private $db;

    /**
     * Veritabanı bağlantısını başlatır.
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Tüm kursları getirir.
     *
     * @return array
     */
    public function getCourses(): array
    {
        $sql = $this->db->query("SELECT * FROM courses");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ID'ye göre kursu getirir.
     *
     * @param int $id
     * @return array
     */
    public function getCourseById(int $id): array
    {
        $sql = "SELECT * FROM courses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gösterim için ID'yi döner.
     *
     * @param mixed $id
     * @return mixed
     */
    public function showCourses($id)
    {
        return $id;
    }

    /**
     * Öğrenci ID'sine göre ders programını getirir.
     *
     * @param int $user_id
     * @return array
     */
    public function getSchedule(int $user_id): array
    {
        $sql = "
            SELECT 
                cs.day_of_week,
                cs.start_time,
                cs.end_time,
                cs.course_id,
                c.title AS course_title
            FROM 
                enrollments e
            JOIN 
                course_schedule cs ON e.course_id = cs.course_id
            JOIN 
                courses c ON cs.course_id = c.id
            WHERE 
                e.student_id = :user_id
            ORDER BY 
                FIELD(cs.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                cs.start_time
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Öğretmen ID'sine göre ders programını getirir.
     *
     * @param int $user_id
     * @return array
     */
    public function getScheduleTeacher(int $user_id): array
    {
        $sql = "
            SELECT cs.*, c.title
            FROM course_schedule cs
            JOIN courses c ON cs.course_id = c.id
            WHERE c.teacher_id = :user_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sayfalanmış ders listesini getirir.
     *
     * @param int $limit   Kaç kayıt çekilecek.
     * @param int $offset  Başlangıç kaydı.
     * @return array       Ders listesi.
     */
    public function getLessons(int $limit = 10, int $offset = 0): array
    {
        $sql = "
            SELECT c.id, c.title, c.description, c.img_url, cat.name AS category, u.username AS teacher
            FROM courses c
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN users u ON c.teacher_id = u.id
            ORDER BY c.created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tüm kategorileri getirir.
     *
     * @return array
     */
    public function getCategories(): array
    {
        $sql = "SELECT * FROM categories";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Yeni kurs ekler.
     *
     * @param string $title
     * @param string $description
     * @param int    $category
     * @param int    $teacher
     * @return bool
     */
    public function addCourse(string $title, string $description, int $category, int $teacher): bool
    {
        $sql = "INSERT INTO courses (title,description,category_id,created_by,teacher_id) 
                VALUES (:title,:description,:category,:created_by,:teacher)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        $createdBy = $_SESSION['user_id'];
        $stmt->bindParam(':created_by', $createdBy, PDO::PARAM_INT);
        $stmt->bindParam(':teacher', $teacher, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Yeni kategori ekler.
     *
     * @param string $title
     * @return bool
     */
    public function addCategory(string $title): bool
    {
        $sql = "INSERT INTO categories (name) VALUES (:title)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        return $stmt->execute();
    }
}
