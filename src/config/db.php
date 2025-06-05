<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $config = $this->loadConfigFromXml(__DIR__ . '/db_config.xml');

        try {
            $this->connection = $this->tryConnect($config);
        } catch(PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            throw new Exception("Не удалось подключиться к БД в Docker");
        }
    }

    private function loadConfigFromXml($filePath) {
        if (!file_exists($filePath)) {
            throw new Exception("Файл конфигурации не найден: $filePath");
        }

        $xml = simplexml_load_file($filePath);
        return [
            'host' => (string)$xml->host,
            'dbname' => (string)$xml->dbname,
            'user' => (string)$xml->user,
            'password' => (string)$xml->password,
            'port' => (string)$xml->port
        ];
    }

    private function tryConnect($config) {
        return new PDO(
            "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}",
            $config['user'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    public static function getConnection() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}
