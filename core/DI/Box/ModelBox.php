<?php

namespace core\DI\Box;

use core\DI\Container;
use core\DI\IRegisterBox;
use core\Validator;

class ModelBox implements IRegisterBox
{
    public function register(Container $container)
    {
        $container->set('model', function ($modelName) use ($container) {
            $model = "\\models\\{$modelName}Model";
            return new $model($container->execute('DBDriver'), new Validator());
        });
    }
}
