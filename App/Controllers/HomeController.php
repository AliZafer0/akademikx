<?php
namespace App\Controllers;
use App\Models\Courses;

class HomeController
{
    public function index()
    {
        session_start();
        require_once __DIR__ . '/../Views/home/index.php';
    }
    public function getCourses()
    {
        $coursesModel = new Courses;
        $courses= $coursesModel->getCourses();
        header('Content-Type: application/json');
        echo json_encode($courses);
        exit;
    }
}
?>