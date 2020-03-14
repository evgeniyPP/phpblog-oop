<?php

namespace core\DI\Box;

use core\DI\Container;
use core\DI\IRegisterBox;
use core\FormBuilder;

class FormBuilderBox implements IRegisterBox
{
    public function register(Container $container)
    {
        $container->set('form', function ($formName, ...$formParams) {
            $form = "\\forms\\{$formName}Form";
            return new FormBuilder(new $form(...$formParams));
        });
    }
}