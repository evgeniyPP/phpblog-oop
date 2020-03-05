<?php

namespace models;

use core\DBDriver;
use core\Validator;

class SessionModel extends BaseModel
{
    const TABLE_NAME = 'sessions';

    protected $schema = [
        'id' => [
            'type' => Validator::INTEGER,
        ],
        'id_user' => [
            'type' => Validator::INTEGER,
        ],
        'sid' => [
            'type' => Validator::STRING,
            'minLength' => 13,
            'maxLength' => 13,
        ],
        'created_at' => [
            'type' => Validator::DATE,
        ],
        'updated_at' => [
            'type' => Validator::DATE,
        ],
    ];

    public function __construct(DBDriver $db, Validator $validator)
    {
        parent::__construct($db, $validator, self::TABLE_NAME);
        $this->validator->setRules($this->schema);
    }

    public function getBySid(string $sid)
    {
        $sql = sprintf('SELECT sessions.id as id, login, password FROM %s JOIN users ON sessions.id_user = users.id WHERE sid = :sid', self::TABLE_NAME);
        return $this->db->runSql($sql, ['sid' => $sid]);
    }
}