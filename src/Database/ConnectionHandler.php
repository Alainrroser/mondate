<?php

namespace App\Database;

use App\Exception\DatabaseConnectionException;
use MySQLi;
use RuntimeException;

class ConnectionHandler {
    
    private static $connection = null;

    /**
     * Der ConnectionHandler implementiert das sogenannte Singleton
     * Entwurfsmuster. Dieses hat zum Ziel, dass von einer Klasse immer nur eine
     * Instanz existiert. Dies wird erreicht, indem der Konstruktor private ist
     * und die Methode getInstance die Instanziierung verwaltet.
     */
    private function __construct() {}
    
    public static function getConnection() {
        if (null === self::$connection) {
            $configFile = '../config.php';

            if (!file_exists($configFile)) {
                throw new RuntimeException('Database config file not found');
            }

            $config = require '../config.php';
            $host = $config['database']['host'];
            $username = $config['database']['username'];
            $password = $config['database']['password'];
            $database = $config['database']['database'];

            self::$connection = new MySQLi($host, $username, $password, $database);
            if (self::$connection->connect_error) {
                $error = self::$connection->connect_error;

                throw new DatabaseConnectionException($error);
            }

            self::$connection->set_charset('utf8');
        }

        return self::$connection;
    }

}
