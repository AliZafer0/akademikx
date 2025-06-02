<?php
namespace App\Controllers;
use App\Models\Courses;
use App\Models\Enrollments;

class HomeController
{
public function index()
{
    session_start();

    if (isset($_SESSION['role']) && $_SESSION['role'] == 'teacher') {
        header('Location: /akademikx/public/teacher-panel');
        exit;
    } elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        header('Location: /akademikx/public/admin');
        exit;
    } else {
        require_once __DIR__ . '/../Views/home/index.php';
    }
}

public function getCourses()
{
    session_start();
    $userId = $_SESSION['user_id'] ?? null;

    $coursesModel = new Courses;
    $enrollmentsModel = new Enrollments;

    $courses = $coursesModel->getCourses();

    foreach ($courses as &$course) {
        $courseId = $course['id'];
        $isEnrolled = $userId ? $enrollmentsModel->isStudentEnrolled($userId, $courseId) : false;
        $course['enroll'] = $isEnrolled ? true : false;
    }

    header('Content-Type: application/json');
    echo json_encode($courses);
    exit;
}


}
?>