<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Enrollments
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function isStudentEnrolled($studentId, $lessonId)
    {
        $sql = "SELECT COUNT(*) as count FROM enrollments WHERE student_id = :student_id AND course_id = :course_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $lessonId, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count['count'] > 0;
    }

    public function addEnrollment($student_id, $course_id)
    {
        $sql = "INSERT INTO enrollments (student_id, course_id) VALUES (:student_id, :course_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getCoursesByUserId($id)
    {
        $sql = "SELECT c.*
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE e.student_id = :student_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':student_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCourseStudentCount($id)
    {
        $sql = "SELECT COUNT(*) as count FROM enrollments WHERE course_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data['count'];
    }
}
