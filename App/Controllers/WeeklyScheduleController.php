<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Courses;
use App\Core\Auth;

class WeeklyScheduleController {

    private $courses;
    private $Auth;

    
    public function __construct()
    {
        session_start();
        $this->courses = new Courses();
        $this->Auth = new Auth();
    }
   
    public function WeeklyScheduleIndex()
    {
        $this->Auth->isLogin();
        require_once __DIR__ . '/../Views/Courses/weekly-schedule.php';
    }

    public function getSchedules($user_id)
    {
        // JSON olarak döndür
        header('Content-Type: application/json');
        echo json_encode( $this->courses->getSchedule($user_id));
      
    }

}
