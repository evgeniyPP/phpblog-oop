<?php

namespace models;

use core\DBDriver;

abstract class BaseModel
{
    protected $db;
    protected $table;
    protected $key;

    public function __construct(DBDriver $db, string $table, string $key)
    {
        $this->db = $db;
        $this->table = $table;
        $this->key = $key;
    }

    public function getAll()
    {
        return $this->db->selectAll($this->table);
    }

    public function getById(int $id)
    {
        ValidateModel::validateId($id);
        return $this->db->selectOne(
            $this->table,
            "$this->key = :id",
            ['id' => $id]
        );
    }

    public function deleteById(int $id)
    {
        ValidateModel::validateId($id);
        return $this->db->delete(
            $this->table,
            "$this->key = :id",
            ['id' => $id]
        );
    }

    public function add(array $props)
    {
        return $this->db->insert($this->table, $props);
    }

    public function editById(array $props, int $id)
    {
        return $this->db->update(
            $this->table,
            $props,
            "$this->key = :id",
            ['id' => $id]
        );
    }
}