<?php

namespace models;

use core\DBDriver;
use core\Exception\DBException;
use core\Exception\NoAuthException;
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

    public function signUp(array $fields)
    {
        $this->validator->execute($fields);

        if (!$this->validator->success) {
            throw new ValidatorException($this->validator->errors);
        }

        $this->add([
            'login' => $this->validator->clean['login'],
            'password' => $this->generateHash($this->validator->clean['password']),
        ],
            false
        );
    }

    public function login(array $fields)
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
            throw new NoAuthException();
        }

        $isAuth = $this->checkPassword($password, $user['password']);

        if (!$isAuth) {
            throw new NoAuthException();
        }

        return [
            'isAuth' => $isAuth,
            'login' => $user['login'],
            'password' => $user['password'],
        ];
    }

    public function checkPassword($pass, $hash)
    {
        if ($this->generateHash($pass) === $hash) {
            return true;
        }
        return false;
    }

    public function generateHash($pass)
    {
        return hash_pbkdf2('sha256', $pass, 'salttlas', 1000, 20);
    }
}