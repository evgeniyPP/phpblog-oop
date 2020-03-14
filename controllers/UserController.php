<?php

namespace controllers;

use core\Exception\AuthException;
use core\Exception\ValidatorException;

class UserController extends BaseController
{
    private $error;
    private $user;

    public function __construct(\core\DI\Container $container, \core\Request $request)
    {
        parent::__construct($container, $request);
        $this->user = $this->container->execute('user', $request);
    }

    public function index()
    {
        if ($this->request->get('COOKIE', 'return_url')) {
            $hasReturnUrl = true;
            $this->error = 'Нет доступа';
        } else {
            $hasReturnUrl = false;
        }

        if ($this->request->isPost()) {
            try {
                try {
                    switch ($_REQUEST['login_form_submit']) {
                        case 'login':
                            $this->user->login($this->request->get('POST'));

                            if ($hasReturnUrl) {
                                unset($_SESSION['return_url']);
                                $this->redirect($this->request->get('SESSION', 'return_url'));
                            } else {
                                $this->redirect();
                            }

                            break;
                        case 'signup':
                            $this->user->signUp($this->request->get('POST'));
                            $this->redirect();
                            break;
                    }
                } catch (ValidatorException $e) {
                    $validationErrors = $e->getErrors();
                }} catch (AuthException $e) {
                $noAuthError = $e->getMessage();
            }
        }

        $form = $this->container->execute(
            'form',
            'Auth',
            $validationErrors ?? null
        );

        $this->title = 'Авторизация | Блог на PHP';
        $this->stylefile = 'login';
        $this->content = $this->build(
            __DIR__ . '/../views/login.html.php',
            [
                'form' => $form,
                'no_auth_error' => $noAuthError ?? null,
            ]
        );
    }

    public function logout()
    {
        $this->user->logout();
        $this->redirect();
    }
}