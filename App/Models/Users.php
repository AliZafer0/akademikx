<?php
namespace App\Models;

use App\Core\Database;
use PDO;


Class Users
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addUser($data)
    {
        $passwordHashed = password_hash($data['password'], PASSWORD_DEFAULT);
        $approved = $data['approved'] ?? 0;

        $sql = "INSERT INTO users (username,password_hash,role,approved) VALUES (:username,:password_hash,:role,:approved)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username',$data['username'],PDO::PARAM_STR);
        $stmt->bindParam(':password_hash',$passwordHashed,PDO::PARAM_STR);
        $stmt->bindParam(':role',$data['role'],PDO::PARAM_STR);
        $stmt->bindParam(':approved',$approved,PDO::PARAM_INT);
        
        return $stmt->execute(); // true ya da false döner
    }
    public function getUsers()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id',$id,PDO::PARAM_STR);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }
    public function getUserByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username',$username,PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }
    public function getUserByRole($role)
    {
        $sql = "SELECT * FROM users WHERE role = :role";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':role',$role,PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
    public function addTeacher($data)
    {
            $sql = "INSERT INTO users (username, password_hash, approved, role) 
            VALUES (:username, :password, :approved, :role)";
            $stmt = $this->db->prepare($sql);

            // bindParam kullanımı
            $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
            $stmt->bindParam(':approved', $data['approv'], PDO::PARAM_INT); // onay durumu sayısal
            $stmt->bindParam(':role', $data['role'], PDO::PARAM_STR);

            return $stmt->execute();
    }
    public function usernameExists($username)
    {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username',$username,PDO::PARAM_STR);
        $stmt->execute();
        $exitsUser = $stmt->fetch(PDO::FETCH_ASSOC);
        return $exitsUser['count'] > 0;
    }
    public function hasRole(string $role, int $id): bool
    {
        $sql = "SELECT role FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

       if($role === $user)
       {
        return true;
       }
       else
       {
        return false;
       }
    }

}
// 🧪 Test amaçlı çalıştırma (yalnızca direkt bu dosya açılırsa çalışsın)
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    require_once __DIR__ . '/../Core/Database.php'; // Veritabanı sınıfını dahil etmeyi unutma
    $userModel = new Users();
    $userModel->hasRole('admin',1);
}
?>