<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Enrollments
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
     * Öğrencinin derse kayıtlı olup olmadığını kontrol eder.
     *
     * @param int $studentId
     * @param int $lessonId
     * @return bool
     */
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

    /**
     * Öğrenciyi derse kaydeder.
     *
     * @param int $student_id
     * @param int $course_id
     * @return bool
     */
    public function addEnrollment($student_id, $course_id)
    {
        $sql = "INSERT INTO enrollments (student_id, course_id) VALUES (:student_id, :course_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Öğrencinin kayıtlı olduğu kursları getirir.
     *
     * @param int $id
     * @return array
     */
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

    /**
     * Dersin öğrenci sayısını döner.
     *
     * @param int $id
     * @return int
     */
    public function getCourseStudentCount($id)
    {
        $sql = "SELECT COUNT(*) as count FROM enrollments WHERE course_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $data['count'];
    }
}
