<?php

namespace core\Exception;

class ValidatorException extends \Exception
{
    private $errors;

    public function __construct(array $errors)
    {
        parent::__construct('Ошибка валидации формы');
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}