<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Teachers
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getTeacherCourses($id)
    {
       $sql = "SELECT * FROM courses WHERE teacher_id = :id";
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':id',$id,PDO::PARAM_INT);
       $stmt->execute();
       return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
   
}
 