<?php
namespace App\Core;

use Core\Database;
use App\Models\Users;
use PDO;

class Auth
{
    /**
     * Auth servisini başlatır ve Users modelini yükler.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Users = new Users();
    }

    /**
     * Kullanıcı adı ve şifreyi doğrular, başarılıysa kullanıcı verisini döner.
     *
     * @param string $username
     * @param string $password
     * @return array|false Başarılıysa kullanıcı verisi, aksi halde false
     */
    public function login($username, $password)
    {
        $user = $this->Users->getUserByUsername($username);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        return $user;
    }

    /**
     * Yeni kullanıcı kaydı oluşturur; kullanıcı adı zaten varsa yönlendirir.
     *
     * @param array $data ['username' => string, 'password' => string, 'role' => string]
     * @return bool Kayıt başarılıysa true
     */
    public function register_user($data)
    {
        $userExists = $this->Users->usernameExists($data['username']);

        if ($userExists) {
            header('Location: register?error=' . urlencode('Bu Kullanıcı Adı Alınmış'));
            exit;
        } else {
            $this->Users->addUser($data);
            return true;
        }
    }

    /**
     * Kullanıcının oturum açıp açmadığını kontrol eder, değilse yönlendirir.
     *
     * @return void
     */
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
