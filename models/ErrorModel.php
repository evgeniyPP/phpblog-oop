<?php

namespace models;

class ErrorModel
{
    public static function checkDBError($stmt)
    {
        $error = $stmt->errorInfo();
        if ($error[0] != \PDO::ERR_NONE) {
            throw new \Exception("Database Error: $error[2]");
        }
    }

    public static function error404()
    {
        header("HTTP/1.1 404 Not Found");
        header('Location: ' . ROOT . "404");
        exit();
    }
}