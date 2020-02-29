<?php

namespace models;

abstract class BaseModel
{
    protected $db;
    protected $table;

    public function __construct(\PDO $db, string $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM $this->table ORDER BY dt DESC";
        $stmt = $this->db->query($sql);
        ErrorModel::checkDBError($stmt);

        return $stmt->fetchAll();
    }

    public function getById(int $id)
    {
        ValidateModel::validateId($id);
        $sql = "SELECT * FROM $this->table WHERE id_post=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);
        ErrorModel::checkDBError($stmt);

        return $stmt->fetch();
    }

    public function deleteById(int $id)
    {
        ValidateModel::validateId($id);
        $sql = "DELETE FROM $this->table WHERE id_post=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);
        ErrorModel::checkDBError($stmt);

        return true;
    }
}
