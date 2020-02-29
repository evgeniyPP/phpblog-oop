<?php

namespace models;

use controllers\LoginController;

class AuthModel
{
    public static function generateHash($pass)
    {
        return hash_pbkdf2('sha256', $pass, 'salttlas', 1000, 20);
    }

    public static function checkAuth()
    {
        if (!(isset($_SESSION['is_auth']) && $_SESSION['is_auth'])) {
            if (!(isset($_COOKIE['login'])
                && $_COOKIE['login'] == 'root'
                && isset($_COOKIE['password'])
                && $_COOKIE['password'] == self::generateHash('toor'))) {
                return false;
            }
            $_SESSION['is_auth'] = true;
        }
        return true;
    }
}