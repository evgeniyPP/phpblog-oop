<?php

namespace core;

use core\Request;
use models\SessionModel;
use models\UserModel;

class User
{
    const NO_USERNAME = 'Аноним';

    private $mUser;
    private $mSession;
    private $request;

    public function __construct(UserModel $mUser, SessionModel $mSession, Request $request)
    {
        $this->mUser = $mUser;
        $this->mSession = $mSession;
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

            $sid = uniqid();
            $this->mSession->add([
                'id_user' => $user['id'],
                'sid' => $sid,
            ]);
            $_SESSION['sid'] = $sid;

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
        $session = $this->request->get('SESSION', 'is_auth');
        $sid = $this->request->get('SESSION', 'sid');

        if ($session && $sid) {
            $user = $this->mSession->getBySid($sid);

            $this->mSession->editById(
                [
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                $user['id']
            );

            $isAuth = true;
            $username = $user['login'];
        } else {
            $isAuth = $this->checkCookie();

            if ($isAuth) {
                $username = $this->request->get('COOKIE', 'login') ?? self::NO_USERNAME;
            }
        }

        return [
            'isAuth' => $isAuth ?? false,
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