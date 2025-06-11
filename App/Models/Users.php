<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Users
{
    private $db;

    /**
     * Veritabanı bağlantısını başlatır.
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Yeni kullanıcı ekler.
     *
     * @param array $data ['username' => string, 'password' => string, 'role' => string, 'approved' => int|null]
     * @return bool
     */
    public function addUser($data)
    {
        $passwordHashed = password_hash($data['password'], PASSWORD_DEFAULT);
        $approved = $data['approved'] ?? 0;

        $sql = "INSERT INTO users (username,password_hash,role,approved) VALUES (:username,:password_hash,:role,:approved)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username',      $data['username'],       PDO::PARAM_STR);
        $stmt->bindParam(':password_hash', $passwordHashed,         PDO::PARAM_STR);
        $stmt->bindParam(':role',          $data['role'],           PDO::PARAM_STR);
        $stmt->bindParam(':approved',      $approved,               PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Tüm kullanıcıları getirir.
     *
     * @return array
     */
    public function getUsers()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ID'ye göre kullanıcı getirir.
     *
     * @param int $id
     * @return array|null
     */
    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Kullanıcı adıyla kullanıcı getirir.
     *
     * @param string $username
     * @return array|null
     */
    public function getUserByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Role göre kullanıcıları getirir.
     *
     * @param string $role
     * @return array
     */
    public function getUserByRole($role)
    {
        $sql = "SELECT * FROM users WHERE role = :role";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':role', $role, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Yeni öğretmen ekler.
     *
     * @param array $data ['username' => string, 'password' => string, 'approved' => int, 'role' => string]
     * @return bool
     */
    public function addTeacher($data)
    {
        $sql = "INSERT INTO users (username, password_hash, approved, role) 
                VALUES (:username, :password, :approved, :role)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
        $stmt->bindParam(':approved', $data['approv'],   PDO::PARAM_INT);
        $stmt->bindParam(':role',     $data['role'],      PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Kullanıcı adı var mı kontrol eder.
     *
     * @param string $username
     * @return bool
     */
    public function usernameExists($username)
    {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }

    /**
     * Kullanıcının belirli bir role sahip olup olmadığını döner.
     *
     * @param string $role
     * @param int    $id
     * @return bool
     */
    public function hasRole(string $role, int $id): bool
    {
        $sql = "SELECT role FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($user && $user['role'] === $role);
    }

    /**
     * ID'ye göre kullanıcıyı siler.
     *
     * @param int $id
     * @return bool
     */
    public function DelUserById($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

// 🧪 Test amaçlı çalıştırma (yalnızca direkt bu dosya açılırsa çalışsın)
// if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
//     require_once __DIR__ . '/../Core/Database.php';
//     $userModel = new Users();
//     $userModel->hasRole('admin', 1);
// }
