<?php

namespace models;

class UserModel extends BaseModel
{
    public function __construct(\PDO $db)
    {
        parent::__construct($db, 'users');
    }

    public function add(string $name)
    {
        $this->validateUser($name);
        $sql = "INSERT INTO $this->table (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $name,
        ]);
        $this->checkError($stmt);

        return $this->db->lastInsertId();
    }

    public function edit(string $name, int $id)
    {
        $this->validateUser($name);
        $this->validateId($id);
        $sql = "UPDATE $this->table SET name=:name WHERE id_user=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'id' => $id,
        ]);
        $this->checkError($stmt);

        return $this->db->lastInsertId();
    }

    private function validateUser($name) {
        if (!preg_match('/^[a-zA-Zа-яА-Я]{2,}$/', $name)) {
            
            throw new \Exception('Некорректное имя');
        }
        echo var_dump(preg_match('/^[a-zA-Zа-яА-Я]{2,}$/', $name));
        return true;
    }
}