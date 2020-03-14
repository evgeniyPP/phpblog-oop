<?php

namespace core\DI\Box;

use core\DB;
use core\DBDriver;
use core\DI\Container;
use core\DI\IRegisterBox;

class DBDriverBox implements IRegisterBox
{
    public function register(Container $container)
    {
        $container->set('DBDriver', function () {
            return new DBDriver(DB::getDBInstance());
        });
    }
}