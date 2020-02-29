<?php

namespace core;

class DB
{
    private static $instance;

    private static function connect()
    {
        $dsn = sprintf('%s:host=%s;dbname=%s', 'mysql', 'localhost', 'lavrik_blog');
        $db = new \PDO($dsn, 'root', '');
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