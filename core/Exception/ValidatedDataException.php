<?php

namespace core\Exception;

class ValidatedDataException extends \Exception
{
    private $errors;

    public function __construct(array $errors)
    {
        parent::__construct('Error in the validation process');
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}