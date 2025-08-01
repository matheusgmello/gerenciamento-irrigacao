<?php
namespace App\Utils;

class ConexaoSQLite
{
    private static $pdo = null;

    public static function obter(): \PDO
    {
        if (self::$pdo === null) {
            $caminho = __DIR__ . '/../../dados/app.sqlite';
            self::$pdo = new \PDO("sqlite:$caminho");
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pdo->exec('PRAGMA foreign_keys = ON;');
        }
        return self::$pdo;
    }
}
