<?php

namespace models;

use core\DBDriver;
use core\Exception\ValidatorException;
use core\Validator;

abstract class BaseModel
{
    protected $db;
    protected $validator;
    protected $table;

    public function __construct(DBDriver $db, Validator $validator, string $table)
    {
        $this->db = $db;
        $this->validator = $validator;
        $this->table = $table;
    }

    public function getAll()
    {
        return $this->db->selectAll($this->table);
    }

    public function getById(int $id)
    {
        return $this->db->selectOne(
            $this->table,
            'id = :id',
            ['id' => $id]
        );
    }

    public function getByUsername(string $login)
    {
        return $this->db->selectOne(
            $this->table,
            'login = :login',
            ['login' => $login]
        );
    }

    public function deleteById(int $id)
    {
        return $this->db->delete(
            $this->table,
            'id = :id',
            ['id' => $id]
        );
    }

    public function add(array $props, bool $needValidation = true)
    {
        if ($needValidation) {
            $this->validator->execute($props);

            if (!$this->validator->success) {
                throw new ValidatorException($this->validator->errors);
            }

            $props = $this->validator->clean;
        }

        return $this->db->insert($this->table, $props);
    }

    public function editById(array $props, int $id)
    {
        $this->validator->execute(
            array_merge($props, ['id' => $id])
        );

        if (!$this->validator->success) {
            throw new ValidatorException($this->validator->errors);
        }

        $props = array_filter(
            $this->validator->clean,
            function ($item) use ($id) {
                return $item !== $id;
            }
        );

        return $this->db->update(
            $this->table,
            $props,
            'id = :id',
            ['id' => $id]
        );
    }
}