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
        $this->checkError($stmt);

        return $stmt->fetchAll();
    }

    public function getById(int $id)
    {
        $this->validateId($id);
        $sql = "SELECT * FROM $this->table WHERE id_post=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);
        $this->checkError($stmt);

        return $stmt->fetch();
    }

    public function deleteById(int $id)
    {
        $this->validateId($id);
        $sql = "DELETE FROM $this->table WHERE id_post=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);
        $this->checkError($stmt);

        return true;
    }

    protected function checkError($stmt)
    {
        $error = $stmt->errorInfo();
        if ($error[0] != \PDO::ERR_NONE) {
            throw new \Exception("Database Error: $error[2]");
        }
    }

    protected function validateId($id) {
        if ($id == null) {
            throw new \Exception('Нет id');
        } elseif (!preg_match('/^\d+$/', $id)) {
            throw new \Exception('Некорректный id');
        }
        return true;
    }
}