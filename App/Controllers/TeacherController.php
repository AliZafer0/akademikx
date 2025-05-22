<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Teachers;
use App\Models\Enrollments;
use App\Models\CourseContents;
use App\Core\Auth;
use App\Models\Courses;

class TeacherController {
    private $teacherModel;
    private $coursesModel;
    private $coursesContents;
    private $Auth;
    private $Courses;

    public function __construct() {
        session_start();
        $this->teacherModel = new Teachers();
        $this->coursesModel = new Enrollments();
        $this->coursesContents = new CourseContents();
        $this->Auth = new Auth();
        $this->Courses = new Courses();

    }

    public function teacherPanelIndex() {
        $this->Auth->isLogin();
        if($_SESSION['role'] == 'teacher' || $_SESSION['role'] == 'admin')
        {
            require_once __DIR__ . '/../Views/teacher/teacher-panel.php';
        }
        else
        {
            $redirectUrl = '/akademikx/public/';
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

   public function getTeacherCourses($id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->teacherModel->getTeacherCourses($id));
    }

    public function getStudentCount($id)
    {
        $studentCount = $this->coursesModel->getCourseStudentCount($id);
        header('Content-Type: application/json');
        echo json_encode(['count' => $studentCount]);
    }

    public function teacherCourseDetailIndex($id)
    {
        $this->Auth->isLogin();
        if($_SESSION['role'] == 'teacher' || $_SESSION['role'] == 'admin')
        {
            require_once __DIR__ . '/../Views/teacher/teacher-course-detail.php';
        }else
        {
            $redirectUrl = '/akademikx/public/';
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    public function teacherAddSchedule()
    {
        $day_of_week = $_POST['day_of_week'] ?? null;
        $start_time = $_POST['start_time'] ?? null;
        $end_time = $_POST['end_time'] ?? null;
        $course_id = $_POST['lesson_id'] ?? null;

        $Add = $this->coursesContents->addSchedule($course_id, $day_of_week, $start_time, $end_time);

        if ($Add === true) {
            // İşlem başarılıysa: Geldiği sayfaya geri dön
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            // Hatalıysa: Geldiği sayfaya geri dön ve URL'ye hata mesajı ekle
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=1');
            exit;
        }
    }
    public function getsTeacherSchedule($user_id)
    {
        header('Content-Type: application/json');
        echo json_encode( $this->Courses->getScheduleTeacher($user_id));
    }

    public function TeacherScheduleIndex()
    {
        $this->Auth->isLogin();
        require_once __DIR__ . '/../Views/Teacher/weekly-schedule-teacher.php';    }

}
 