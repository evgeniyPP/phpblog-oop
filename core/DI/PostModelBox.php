<?php

namespace core\DI;

use core\DB;
use core\DBDriver;
use core\Validator;
use models\PostModel;

class PostModelBox implements IRegisterBox
{
    public function register(Container $container)
    {
        $container->set('postModel', function () {
            $DBInstance = DB::getDBInstance();
            return new PostModel(new DBDriver($DBInstance), new Validator());
        });
    }
}