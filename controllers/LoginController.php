<?php

namespace controllers;

use models\AuthModel;

class LoginController extends BaseController
{
    public function index()
    {
        $error = '';

        if ($this->request->get('SESSION', 'is_auth')) {
            unset($_SESSION['is_auth']);
        }
        if ($this->request->get('COOKIE', 'login')) {
            setcookie('login', null, 0, '/');
        }
        if ($this->request->get('COOKIE', 'password')) {
            setcookie('password', null, 0, '/');
        }

        if ($this->request->get('COOKIE', 'return_url')) {
            $hasReturnUrl = true;
            $error = 'Нет доступа';
        } else {
            $hasReturnUrl = false;
        }

        if ($this->request->isPost()) {
            if ($this->request->get('POST', 'login') == 'root' && $this->request->get('POST', 'password') == 'toor') {
                $_SESSION['is_auth'] = true;

                if ($this->request->get('POST', 'remember')) {
                    setcookie(
                        'login',
                        $this->request->get('POST', 'login'),
                        time() + 3600 * 24 * 7, '/'
                    );
                    setcookie('password',
                        AuthModel::generateHash($this->request->get('POST', 'password')),
                        time() + 3600 * 24 * 7, '/'
                    );
                }

                if ($hasReturnUrl) {
                    $return_url = $this->request->get('SESSION', 'return_url');
                    unset($_SESSION['return_url']);
                    header('Location: ' . ROOT . $return_url);
                    exit();
                } else {
                    header('Location: ' . ROOT);
                    exit();
                }
            } else {
                $error = "Неверные данные";
            }
        }

        $this->title = 'Авторизация | Блог на PHP';
        $this->stylefile = 'login';
        $this->content = $this->build(
            __DIR__ . '/../views/login.html.php',
            [
                'error' => $error,
            ]
        );
    }
}