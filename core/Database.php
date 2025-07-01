<?php
namespace Codez\DistrictLounge\Core;

class Database
{
    private static $instance = null;
    private $connexion;

    private function __construct($host, $username, $password, $database)
    {
        try {
            $this->connexion = new \PDO(
                "mysql:host=$host;dbname=$database;charset=utf8mb4",
                $username,
                $password
            );
            $this->connexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            require_once __DIR__ . '/config.php';
            self::$instance = new self(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        }
        return self::$instance;
    }

    public function getConnexion()
    {
        return $this->connexion;
    }
}
