<?php

namespace core\DI;

use core\DB;
use core\DBDriver;
use core\Validator;
use models\UserModel;

class UserModelBox implements IRegisterBox
{
    public function register(Container $container)
    {
        $container->set('userModel', function () {
            $DBInstance = DB::getDBInstance();
            return new UserModel(new DBDriver($DBInstance), new Validator());
        });
    }
}