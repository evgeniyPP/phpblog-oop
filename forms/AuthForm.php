<?php

namespace forms;

use core\Form;

class AuthForm extends Form
{
    public function __construct()
    {
        $this->formName = 'auth';
        $this->method = \METHOD_POST;
        $this->fields = [
            [
                'name' => 'login',
                'type' => 'text',
                'placeholder' => 'Введите логин',
                'label' => [
                    'value' => 'Логин',
                ],
            ],
            [
                'name' => 'password',
                'type' => 'password',
                'placeholder' => 'Введите пароль',
                'label' => [
                    'value' => 'Пароль',
                ],
            ],
            [
                'name' => 'remember',
                'type' => 'checkbox',
                'label' => [
                    'class' => 'remember',
                    'value' => '<span></span>Запомнить',
                    'positionAfter' => true,
                ],
            ],
        ];
    }
}