<?php
namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private string $host     = 'localhost';
    private string $dbname   = 'akademikx_db';
    private string $username = 'root';
    private string $password = '';

    /**
     * PDO bağlantısını oluşturur.
     *
     * @return void
     */
    private function __construct()
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Veri Tabanı Bağlantısı Hatası: " . $e->getMessage());
        }
    }

    /**
     * Singleton örneğini döner.
     *
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Mevcut PDO bağlantısını döner.
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
