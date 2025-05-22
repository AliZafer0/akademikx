<?php
namespace App\Core;

use Core\Database;
use App\Models\Users;
use PDO;

class Auth
{

    public function __construct()
    {
        $this->Users = new Users();
    }

   public function login($username, $password)
    {
        $user = $this->Users->getUserByUsername($username);

        if (!$user) return false;

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        return $user;
    }
    public function register_user($data)
    {

        $userExitis = $this->Users->usernameExists($data['username']);

        if($userExitis)
        {
            header('Location: register?error=' . urlencode('Bu Kullanıcı Adı Alınmış'));
            exit;
        }
        else{
            $this->Users->addUser($data);
            return true;
        }
    }
    public function isLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            // Güvenli bir varsayılan yönlendirme
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/akademikx/public/login';
            header('Location: ' . $redirectUrl);
            exit;
        }
    }
}
?>