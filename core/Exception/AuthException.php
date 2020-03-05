<?php

namespace core\Exception;

class AuthException extends \Exception
{
    const MESSAGE = 'Неверные данные';

    public function __construct($message = self::MESSAGE)
    {
        parent::__construct($message);
    }
}
