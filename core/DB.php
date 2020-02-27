<?php

namespace core;

class DB
{
    public static function connect()
    {
        $dsn = sprintf('%s:host=%s;dbname=%s', 'mysql', 'localhost', 'lavrik_blog');
        $db = new \PDO($dsn, 'root', '');
        $db->exec('SET NAMES UTF8');
        return $db;
    }
}