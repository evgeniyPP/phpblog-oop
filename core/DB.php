<?php

namespace core;

class DB
{
    private static $instance;

    private static function connect()
    {
        $options = [
            // \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];
        $dsn = sprintf('%s:host=%s;dbname=%s', 'mysql', 'localhost', 'lavrik_blog');
        $db = new \PDO($dsn, 'root', '', $options);
        $db->exec('SET NAMES UTF8');
        return $db;
    }

    public static function getDBInstance()
    {
        if (self::$instance === null) {
            self::$instance = self::connect();
        }
        return self::$instance;
    }
}