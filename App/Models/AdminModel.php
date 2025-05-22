<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class AdminModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUserStat()
    {
        $sql_teacher = "SELECT COUNT(*) as teacher_count FROM users WHERE role = 'teacher'";
        $sql_student = "SELECT COUNT(*) as student_count FROM users WHERE role = 'student'";
        $sql_total = "SELECT COUNT(*) as total_count FROM users";

        $stmt = $this->db->prepare($sql_teacher);
        $stmt->execute();
        $student_count = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare($sql_student);
        $stmt->execute();
        $teacher_count = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $stmt = $this->db->prepare($sql_total);
        $stmt->execute();
        $total_count = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
        'teachers' => $teacher_count,
        'students' => $student_count,
        'total' => $total_count,
         ];
        
    }

}