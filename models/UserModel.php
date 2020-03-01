<?php

namespace models;

use core\DBDriver;
use core\Validator;

class UserModel extends BaseModel
{
    const TABLE_NAME = 'users';
    const PRIMARY_KEY = 'id_user';

    protected $schema = [
        'id' => [
            'type' => Validator::INTEGER,
        ],
        'name' => [
            'required' => true,
            'nullable' => false,
            'type' => Validator::STRING,
            'minLength' => 2,
            'maxLength' => 256,
        ],
    ];

    public function __construct(\PDO $db, Validator $validator)
    {
        parent::__construct($db, $validator, self::TABLE_NAME, self::PRIMARY_KEY);
        $this->validator->setRules($this->schema);
    }
}