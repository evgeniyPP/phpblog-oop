<?php

namespace core\Exception;

class Error404Exception extends \Exception
{

    public function __construct(string $message = 'Страница не найдена', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}