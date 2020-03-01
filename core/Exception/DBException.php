<?php

namespace core\Exception;

class DBException extends \Exception
{

    public function __construct(string $message = 'Ошибка базы данных', int $code = 500)
    {
        parent::__construct($message, $code);
    }
}