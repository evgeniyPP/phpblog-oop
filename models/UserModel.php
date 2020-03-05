<?php

namespace models;

use core\DBDriver;
use core\Exception\AuthException;
use core\Exception\DBException;
use core\Exception\UsernameTakenException;
use core\Exception\ValidatorException;
use core\Validator;

class UserModel extends BaseModel
{
    const TABLE_NAME = 'users';

    protected $schema = [
        'id' => [
            'type' => Validator::INTEGER,
        ],
        'login' => [
            'required' => true,
            'nullable' => false,
            'type' => Validator::STRING,
            'minLength' => 4,
            'maxLength' => 50,
        ],
        'password' => [
            'required' => true,
            'nullable' => false,
            'type' => Validator::STRING,
            'minLength' => 4,
            'maxLength' => 50,
        ],
    ];

    public function __construct(DBDriver $db, Validator $validator)
    {
        parent::__construct($db, $validator, self::TABLE_NAME);
        $this->validator->setRules($this->schema);
    }

    public function getByUsername(string $login)
    {
        return $this->db->selectOne(
            $this->table,
            'login = :login',
            ['login' => $login]
        );
    }

    public function signUp(array $fields)
    {
        $this->validator->execute($fields);

        if (!$this->validator->success) {
            throw new ValidatorException($this->validator->errors);
        }

        try {
            $this->add([
                'login' => $this->validator->clean['login'],
                'password' => $this->generateHash($this->validator->clean['password']),
            ],
                false
            );
        } catch (DBException $e) {
            throw new AuthException('Такой пользователь уже существует');
        }
    }

    public function login(array $fields, bool $isHashed = false)
    {
        $this->validator->execute($fields);

        if (!$this->validator->success) {
            throw new ValidatorException($this->validator->errors);
        }

        $login = $this->validator->clean['login'];
        $password = $this->validator->clean['password'];

        try {
            $user = $this->getByUsername($login);
        } catch (DBException $e) {
            throw new AuthException();
        }

        $isAuth = $this->checkPassword($password, $user['password'], $isHashed);

        if (!$isAuth) {
            throw new AuthException();
        }

        return [
            'isAuth' => $isAuth,
            'id' => $user['id'],
            'login' => $user['login'],
            'password' => $user['password'],
        ];
    }

    public function checkPassword($pass, $hash, bool $isHashed = false)
    {
        if ($isHashed) {
            if ($pass === $hash) {
                return true;
            }
        } else {
            if ($this->generateHash($pass) === $hash) {
                return true;
            }
        }

        return false;
    }

    public function generateHash($pass)
    {
        return hash_pbkdf2('sha256', $pass, 'salttlas', 1000, 20);
    }
}