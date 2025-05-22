<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Users;
use App\Core\Auth;
class SettingsController {
    private $UserModel;
    private $Auth;

    public function __construct() {
        session_start();
        $this->UserModel = new Users();
        $this->Auth = new Auth();

    }

    public function settingsIndex()
    {
        $this->Auth->isLogin();
        require_once __DIR__ . '/../Views/settings/settings.php';
    }
}
 