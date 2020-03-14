<?php

namespace core\DI\Box;

use core\DI\Container;
use core\DI\IRegisterBox;
use core\User;

class UserBox implements IRegisterBox
{
    public function register(Container $container)
    {
        $container->set('user', function ($request) use ($container) {
            $mUser = $container->execute('model', 'User');
            $mSession = $container->execute('model', 'Session');
            return new User($mUser, $mSession, $request);
        });
    }
}