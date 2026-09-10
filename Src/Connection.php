<?php

require_once __DIR__ . "/../bootstrap.php";

/**
 * impede mensagens de "constante desconhecida"
 * @var string DB_HOST
 * @var string DB_PORT
 * @var string DB_NAME
 * @var string DB_CHARSET
 * @var string DB_USERNAME
 * @var string DB_PASSWORD
 */

class Connection
{
    private static $pdo;
    public static function conectar()
    {
        if (!self::$pdo) {
            self::$pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . "dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USERNAME, DB_PASSWORD);
        }
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$pdo;
    }
}
