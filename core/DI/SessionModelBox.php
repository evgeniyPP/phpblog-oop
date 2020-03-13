<?php

namespace core\DI;

use core\DB;
use core\DBDriver;
use core\Validator;
use models\SessionModel;

class SessionModelBox implements IRegisterBox
{
    public function register(Container $container)
    {
        $container->set('sessionModel', function () {
            $DBInstance = DB::getDBInstance();
            return new SessionModel(new DBDriver($DBInstance), new Validator());
        });
    }
}