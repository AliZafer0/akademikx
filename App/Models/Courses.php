<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Courses
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getCourses()
    {
        $sql = $this->db->query("SELECT * FROM courses");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseById($id)
    {
        $sql = "SELECT * FROM courses WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function showCourses($id)
    {
        return $id;
    }
    public function getSchedule($user_id)
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
        $stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function getScheduleTeacher($user_id)
    {
       $sql = "
            SELECT cs.*, c.title
            FROM course_schedule cs
            JOIN Courses c ON cs.course_id = c.id
            WHERE c.teacher_id = :user_id
        ";


        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id',$user_id,PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    /**
     * Dersleri (lessons) getirir.
     * @param int $limit Kaç kayıt çekilecek.
     * @param int $offset Başlangıç kaydı.
     * @return array Ders listesi.
     */
    public function getLessons($limit = 10, $offset = 0): array
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
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategories()
    {
        $sql = "SELECT * FROM categories";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function addCourse($title,$description,$category,$teacher)
    {
        $sql = "INSERT INTO courses (title,description,category_id,created_by,teacher_id) VALUES (:title,:description,:category,:created_by,:teacher)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title',$title,PDO::PARAM_STR);
        $stmt->bindParam(':description',$description,PDO::PARAM_STR);
        $stmt->bindParam(':category',$category,PDO::PARAM_INT);
        $stmt->bindParam(':created_by',$_SESSION['user_id'],PDO::PARAM_STR);
        $stmt->bindParam(':teacher',$teacher,PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function addCategory($title)
    {
        $sql = "INSERT INTO categories (name) VALUES (:title)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title',$title,PDO::PARAM_STR);
        return $stmt->execute();
    }
}
