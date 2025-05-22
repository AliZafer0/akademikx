<?php 
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private $host = 'localhost';
    private $dbname = 'akademikx_db';
    private  $username = 'root';
    private $password = '';


    private function __construct()
    {
        try {
           $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
           );
           $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); 
        }catch(PDOException $e){
            die("Veri Tabanı Bağlantısı Hatası" . $e->getMessage());
        }
    }
    
    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

?> 