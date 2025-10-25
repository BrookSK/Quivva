<?php
namespace App\core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/config.php';
            $db = $config['db'];
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $db['host'], $db['port'], $db['name'], $db['charset']);
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            try {
                self::$instance = new PDO($dsn, $db['user'], $db['pass'], $options);
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
