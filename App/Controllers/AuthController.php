<?php
namespace App\Controllers;

require_once __DIR__ . '../../../vendor/autoload.php';

use App\Core\Auth;

class AuthController
{
    protected $Auth;

    /**
     * Sınıfı başlatır, oturumu açar ve Auth servisini yükler.
     *
     * @return void
     */
    public function __construct()
    {
        session_start();
        $this->Auth = new Auth();
    }

    /**
     * Giriş sayfasını gösterir veya zaten giriş yapılmışsa ana sayfaya yönlendirir.
     *
     * @return void
     */
    // GET /login
    public function loginIndex()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /akademikx/public/');
            exit;
        } else {
            require_once __DIR__ . '/../Views/Auth/login.php';
        }
    }

    /**
     * POST isteği ile kullanıcı girişini kontrol eder, başarılıysa oturum verilerini oluşturur ve ana sayfaya yönlendirir; başarısızsa hata mesajıyla login sayfasına yönlendirir.
     *
     * @return void
     */
    // POST /login_check.php
    public function login_check()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->Auth->login($username, $password);

        if ($user) {
            $_SESSION["user_id"]  = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"]     = $user["role"];

            header('Location: /akademikx/public/');
            exit;
        } else {
            header('Location: login?error=' . urlencode('Kullanıcı Adı Veya Şifre hatalı'));
            exit;
        }
    }

    /**
     * Oturumu sonlandırır ve giriş sayfasına yönlendirir.
     *
     * @return void
     */
    // GET /logout
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /akademikx/public/login");
        exit;
    }

    /**
     * Kayıt sayfasını gösterir veya zaten giriş yapılmışsa ana sayfaya yönlendirir.
     *
     * @return void
     */
    // GET /register
    public function registerIndex()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /akademikx/public/');
            exit;
        } else {
            require_once __DIR__ . '/../Views/Auth/register.php';
        }
    }

    /**
     * POST isteği ile yeni öğrenci kaydı oluşturur ve login sayfasına yönlendirir.
     *
     * @return void
     */
    // POST /register_student.php
    public function register()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $role     = 'student';

        $data = [
            'username' => $username,
            'password' => $password,
            'role'     => $role
        ];

        if ($this->Auth->register_user($data)) {
            header('Location: /akademikx/public/login');
            exit;
        }
    }
}
