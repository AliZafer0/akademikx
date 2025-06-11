<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Courses;
use App\Core\Auth;

class WeeklyScheduleController {

    private $courses;
    private $Auth;

    /**
     * Sınıfı başlatır, oturumu açar ve Courses modeli ile Auth servisini yükler.
     *
     * @return void
     */
    public function __construct()
    {
        session_start();
        $this->courses = new Courses();
        $this->Auth    = new Auth();
    }

    /**
     * Hafta programı sayfasını görüntüler.
     *
     * @return void
     */
    // GET /weekly-schedule
    public function WeeklyScheduleIndex()
    {
        $this->Auth->isLogin();
        require_once __DIR__ . '/../Views/Courses/weekly-schedule.php';
    }

    /**
     * API: Belirtilen kullanıcıya ait haftalık programı JSON olarak döner.
     *
     * @link GET /get-schedules/{id}
     * @param int $user_id Kullanıcı ID’si
     * @return void
     */
    public function getSchedules($user_id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->courses->getSchedule($user_id));
    }

}
