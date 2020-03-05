<?php

namespace controllers;

use core\DB;
use core\DBDriver;
use core\Exception\NoAuthException;
use core\Exception\ValidatorException;
use core\Request;
use core\User;
use core\Validator;
use models\UserModel;

class UserController extends BaseController
{
    private $error;
    private $user;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $mUser = new UserModel(new DBDriver(DB::getDBInstance()), new Validator());
        $this->user = new User($mUser, $request);
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
                }} catch (NoAuthException $e) {
                $noAuthError = $e->getMessage();
            }
        }

        $this->title = 'Авторизация | Блог на PHP';
        $this->stylefile = 'login';
        $this->content = $this->build(
            __DIR__ . '/../views/login.html.php',
            [
                'no_auth_error' => $noAuthError ?? null,
                'is_validation_errors' => boolval($validationErrors) ?? false,
                'login_errors' => $loginErrors ?? [],
                'password_errors' => $passwordErrors ?? [],
            ]
        );
    }

    public function logout()
    {
        $this->user->logout();
        $this->redirect();
    }
}