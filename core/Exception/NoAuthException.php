<?php

namespace core\Exception;

class NoAuthException extends \Exception
{
    const MESSAGE = 'Неверные данные';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}