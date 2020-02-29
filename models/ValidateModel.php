<?php

namespace models;

class ValidateModel
{
    public static function validateId($id)
    {
        if ($id == null) {
            throw new \Exception('Нет id');
        } elseif (!preg_match('/^\d+$/', $id)) {
            throw new \Exception('Некорректный id');
        }
        return true;
    }

    public static function validatePost($title, $content)
    {
        if ($title == '' || $content == '') {
            throw new \Exception('Заполните все поля');
        }

        return true;
    }

    public static function validateUser($name)
    {
        if (!preg_match('/^[a-zA-Zа-яА-Я]{2,}$/', $name)) {

            throw new \Exception('Некорректное имя');
        }
        echo var_dump(preg_match('/^[a-zA-Zа-яА-Я]{2,}$/', $name));
        return true;
    }
}