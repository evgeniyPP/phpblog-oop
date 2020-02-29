<?php

namespace models;

class PostModel extends BaseModel
{
    public function __construct(\PDO $db)
    {
        parent::__construct($db, 'posts');
    }

    public function add(string $title, string $content)
    {
        ValidateModel::validatePost($title, $content);
        $sql = "INSERT INTO $this->table (title, content) VALUES (:title, :content)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'title' => $title,
            'content' => $content,
        ]);
        ErrorModel::checkDBError($stmt);

        return $this->db->lastInsertId();
    }

    public function edit(string $title, string $content, int $id)
    {
        ValidateModel::validatePost($title, $content);
        ValidateModel::validateId($id);
        $sql = "UPDATE $this->table SET title=:title, content=:content WHERE id_post=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'id' => $id,
        ]);
        ErrorModel::checkDBError($stmt);

        return $this->db->lastInsertId();
    }
}
