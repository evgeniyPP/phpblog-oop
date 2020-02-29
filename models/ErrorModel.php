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
}