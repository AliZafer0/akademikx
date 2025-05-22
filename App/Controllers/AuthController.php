<?php
namespace App\Controllers;

require_once __DIR__ . '../../../vendor/autoload.php';

use App\Core\Auth;

class AuthController
{
    protected $Auth;

    public function __construct()
    {
        session_start();
        $this->Auth = new Auth();
    }

    public function loginIndex()
    {
        if(isset($_SESSION['user_id']))
        {
            header('Location: /akademikx/public/');
            exit;
        }
        else{
            require_once __DIR__ . '/../Views/Auth/login.php';
        }
    }

    public function login_check()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->Auth->login($username, $password);

        if ($user) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];

            header('Location: /akademikx/public/');
            exit;
        } else {
            header('Location: login?error=' . urlencode('Kullanıcı Adı Veya Şifre hatalı'));
            exit;
        }
    }
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /akademikx/public/login");
        exit;
    }
    public function registerIndex()
    {
        if(isset($_SESSION['user_id']))
        {
            header('Location: /akademikx/public/');
            exit;
        }
        else{
            require_once __DIR__ . '/../Views/Auth/register.php';
        }
    }
    public function register()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = 'student';

        $data = [
            'username' => $username,
            'password' => $password,
            'role' => $role
        ];

        if($this->Auth->register_user($data))
        {
            header('Location: /akademikx/public/login');
            exit;
        }
    }


}
