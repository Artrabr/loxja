<?php

class Conexao
{
    private static $pdo;
    public static function conectar()
    {
        if (!self::$pdo) {
            self::$pdo = new PDO(
                "mysql:host=localhost;dbname=progint;charset=utf8mb4",
                "root",
                ""
            );
        }
        return self::$pdo;
    }
}