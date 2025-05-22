<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Courses;
use App\Core\Auth;

class AboutUsController {

    private $courses;
    private $Auth;

    
    public function __construct()
    {
        session_start();
        $this->courses = new Courses();
        $this->Auth = new Auth();
    }
   
    public function aboutUsIndex()
    {
        require_once __DIR__ . '/../Views/settings/about.php';
    }


}
