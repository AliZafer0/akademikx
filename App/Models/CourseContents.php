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

    public function getLessonContentsById($id)
    {
        $sql = "SELECT * FROM course_contents WHERE course_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLessonScheduleById($id)
    {
        $sql = "SELECT * FROM course_schedule WHERE course_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fileDetails($filename)
    {
        $sql = "SELECT * FROM course_contents WHERE file_url = :filename";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':filename', $filename, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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
}
