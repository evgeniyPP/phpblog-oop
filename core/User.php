<?php

namespace core;

use core\Request;
use models\UserModel;

class User
{
    private $mUser;
    private $request;

    public function __construct(UserModel $mUser, Request $request)
    {
        $this->mUser = $mUser;
        $this->request = $request;
    }

    public function signUp($fields)
    {
        $this->mUser->signUp($fields);
        $this->login($fields);
    }

    public function login($fields, bool $isHashed = false)
    {
        $user = $this->mUser->login($fields, $isHashed);

        if ($user['isAuth']) {
            $_SESSION['is_auth'] = true;
            $_SESSION['login'] = $user['login'];

            if (isset($fields['remember'])) {
                setcookie(
                    'login',
                    $user['login'],
                    time() + 3600 * 24 * 7, '/'
                );
                setcookie(
                    'password',
                    $user['password'],
                    time() + 3600 * 24 * 7, '/'
                );
            }
        }

        return $user['isAuth'];
    }

    public function logout()
    {
        if ($this->request->get('SESSION', 'is_auth')) {
            unset($_SESSION['is_auth']);
        }

        if ($this->request->get('SESSION', 'login')) {
            unset($_SESSION['login']);
        }

        if ($this->request->get('COOKIE', 'login')) {
            setcookie('login', null, 0, '/');
        }

        if ($this->request->get('COOKIE', 'password')) {
            setcookie('password', null, 0, '/');
        }
    }

    public function checkAuth()
    {
        $isAuth = false;

        if ($this->request->get('SESSION', 'is_auth')) {
            # TODO: check DBSession
            $isAuth = true;
            $username = $this->request->get('SESSION', 'login');
        } else {
            $isAuth = $this->checkCookie();

            if ($isAuth) {
                $username = $this->request->get('COOKIE', 'login');
            }
        }

        return [
            'isAuth' => $isAuth,
            'username' => $username ?? null,
        ];
    }

    private function checkCookie()
    {
        $login = $this->request->get('COOKIE', 'login') ?? null;
        $password = $this->request->get('COOKIE', 'password') ?? null;

        if ($login && $password) {
            $isAuth = $this->login([
                'login' => $login,
                'password' => $password,
            ],
                true
            );
        }

        return $isAuth ?? false;
    }
}