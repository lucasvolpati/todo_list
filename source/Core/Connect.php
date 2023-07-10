<?php

namespace  Source\Core;

use PDO;
use PDOException;

class Connect
{
    private static $instance;
    private const OPTIONS = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ];

    public static function getInstance():PDO
    {
        if(empty(self::$instance)) {
            try {
                self::$instance = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                    DB_USER,
                    DB_PASS,
                    self::OPTIONS
                );
            } catch (PDOException $exception) {
                die("Opss! Erro ao conectar... Erro: {$exception}");
            }
        }

        return self::$instance;
    }
}
