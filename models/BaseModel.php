<?php

namespace models;

use core\DBDriver;
use core\Validator;

abstract class BaseModel
{
    protected $db;
    protected $validator;
    protected $table;
    protected $key;

    public function __construct(DBDriver $db, Validator $validator, string $table, string $key)
    {
        $this->db = $db;
        $this->validator = $validator;
        $this->table = $table;
        $this->key = $key;
    }

    public function getAll()
    {
        return $this->db->selectAll($this->table);
    }

    public function getById(int $id)
    {
        return $this->db->selectOne(
            $this->table,
            "$this->key = :id",
            ['id' => $id]
        );
    }

    public function deleteById(int $id)
    {
        return $this->db->delete(
            $this->table,
            "$this->key = :id",
            ['id' => $id]
        );
    }

    public function add(array $props)
    {
        $this->validator->execute($props);

        if (!$this->validator->success) {
            var_dump($this->validator->errors);
            throw new \Exception('Errors in the validation process'); # TODO error handling
        }
        $props = $this->validator->clean;

        return $this->db->insert($this->table, $props);
    }

    public function editById(array $props, int $id)
    {
        $this->validator->execute(
            array_merge($props, ['id' => $id])
        );

        if (!$this->validator->success) {
            throw new \Exception($this->validator->errors); # TODO error handling
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
            "$this->key = :id",
            ['id' => $id]
        );
    }
}