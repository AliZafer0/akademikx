<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Users;
use App\Core\Auth;

class SettingsController {
    private $UserModel;
    private $Auth;

    /**
     * Sınıfı başlatır, oturumu açar ve Users modeli ile Auth servisini yükler.
     *
     * @return void
     */
    public function __construct() {
        session_start();
        $this->UserModel = new Users();
        $this->Auth      = new Auth();
    }

    /**
     * Ayarlar sayfasını görüntüler.
     * Kullanıcının oturum açmış olması gerekir.
     *
     * @return void
     */
    // GET /settings
    public function settingsIndex()
    {
        $this->Auth->isLogin();
        require_once __DIR__ . '/../Views/settings/settings.php';
    }
}
