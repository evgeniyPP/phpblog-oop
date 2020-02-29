<?php

namespace models;

use core\DBDriver;

class PostModel extends BaseModel
{
    const TABLE_NAME = 'posts';
    const PRIMARY_KEY = 'id_post';

    public function __construct(DBDriver $db)
    {
        parent::__construct($db, self::TABLE_NAME, self::PRIMARY_KEY);
    }
}