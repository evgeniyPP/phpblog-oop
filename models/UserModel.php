<?php

namespace models;

class UserModel extends BaseModel
{
    public function __construct(\PDO $db)
    {
        parent::__construct($db, 'users', 'id_user');
    }

    public function add(string $name)
    {
        ValidateModel::validateUser($name);
        $sql = "INSERT INTO $this->table (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $name,
        ]);
        ErrorModel::checkDBError($stmt);

        return $this->db->lastInsertId();
    }

    public function edit(string $name, int $id)
    {
        ValidateModel::validateUser($name);
        ValidateModel::validateId($id);
        $sql = "UPDATE $this->table SET name=:name WHERE id_user=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'id' => $id,
        ]);
        ErrorModel::checkDBError($stmt);

        return $this->db->lastInsertId();
    }
}