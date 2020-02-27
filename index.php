<?php

use core\DB;
use models\PostModel;
use models\UserModel;

function __autoload($classname)
{
    include_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
}

$db = DB::connect();

$mPost = new PostModel($db);
$mUser = new UserModel($db);

$iam = $mUser->edit('Евгений', 1);

// $post = $mPost->deleteById(12);

// var_dump($post);