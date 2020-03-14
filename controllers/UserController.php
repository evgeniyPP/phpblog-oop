<?php

namespace controllers;

use core\DI\Container;
use core\Exception\AuthException;
use core\Exception\ValidatorException;
use core\FormBuilder;
use core\Request;
use core\User;
use forms\AuthForm;

class UserController extends BaseController
{
    private $error;
    private $user;

    public function __construct(Container $container, Request $request)
    {
        parent::__construct($container, $request);

        $mUser = $this->container->execute('userModel');
        $mSession = $this->container->execute('sessionModel');
        $this->user = new User($mUser, $mSession, $request);
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
                    $loginErrors = $validationErrors['login'] ?? null;
                    $passwordErrors = $validationErrors['password'] ?? null;
                }} catch (AuthException $e) {
                $noAuthError = $e->getMessage();
            }
        }

        $authForm = new FormBuilder(
            new AuthForm($validationErrors ?? null)
        );

        $this->title = 'Авторизация | Блог на PHP';
        $this->stylefile = 'login';
        $this->content = $this->build(
            __DIR__ . '/../views/login.html.php',
            [
                'form' => $authForm,
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