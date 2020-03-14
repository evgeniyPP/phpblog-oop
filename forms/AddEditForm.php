<?php

namespace forms;

use core\Form;

class AddEditForm extends Form
{
    public function __construct(array $values = [], $errors)
    {
        $this->formName = 'addedit';
        $this->method = \METHOD_POST;
        $this->fields = [
            [
                'name' => 'title',
                'type' => 'text',
                'placeholder' => 'Введите название',
                'class' => 'post__title',
                'value' => $values['title'] ?? null,
                'errors' => $errors['title'] ?? null,
                'label' => [
                    'value' => 'Название поста',
                ],
            ],
            [
                'name' => 'content',
                'type' => 'textarea',
                'placeholder' => 'Введите текст поста',
                'class' => 'post__content',
                'value' => $values['content'] ?? null,
                'errors' => $errors['content'] ?? null,
                'label' => [
                    'value' => 'Текст поста',
                ],
            ],
        ];
    }
}