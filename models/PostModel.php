<?php

namespace models;

use core\DBDriver;
use core\Validator;

class PostModel extends BaseModel
{
    const TABLE_NAME = 'posts';
    const PRIMARY_KEY = 'id_post';

    protected $schema = [
        'id' => [
            'type' => Validator::INTEGER,
        ],
        'title' => [
            'required' => true,
            'nullable' => false,
            'type' => Validator::STRING,
            'minLength' => 5,
            'maxLength' => 256,
        ],
        'content' => [
            'required' => true,
            'nullable' => false,
            'type' => Validator::STRING,
            'minLength' => 100,
        ],
    ];

    public function __construct(DBDriver $db, Validator $validator)
    {
        parent::__construct($db, $validator, self::TABLE_NAME, self::PRIMARY_KEY);
        $this->validator->setRules($this->schema);
    }
}