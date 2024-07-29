<?php
namespace Database;

use DateTime;
use PDO;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $sDsn = "mysql:dbname=" . getenv('DATABASE_NAME') . ";host=" . getenv('PMA_HOST');
        $this->connection = new PDO($sDsn, getenv('MYSQL_USER'), getenv('MYSQL_ROOT_PASSWORD'));
    
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

}
