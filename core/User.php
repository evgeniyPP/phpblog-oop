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
    }

    public function login($fields)
    {
        $user = $this->mUser->login($fields);

        if ($user['isAuth']) {
            $_SESSION['is_auth'] = true;

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
        } else {
            $isAuth = $this->checkCookie();
        }

        return $isAuth;
    }

    private function checkCookie()
    {
        $login = $this->request->get('COOKIE', 'login') ?? null;
        $password = $this->request->get('COOKIE', 'password') ?? null;

        if ($login && $password) {
            $isAuth = $this->login([
                'login' => $login,
                'password' => $password,
            ]);
        }

        return $isAuth ?? false;
    }
}