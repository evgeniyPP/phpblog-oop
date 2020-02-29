<?php

namespace core;

use models\ErrorModel;

class DBDriver
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function selectAll(string $table)
    {
        $sql = "SELECT * FROM {$table} ORDER BY dt DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        ErrorModel::checkDBError($stmt);

        return $stmt->fetchAll();
    }

    public function selectOne(string $table, array $where)
    {
        $whereMask = $this->getWhereMask($where);
        $sql = sprintf('SELECT * FROM %s WHERE %s', $table, $whereMask);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $where['key'] => $where['value'],
        ]);
        ErrorModel::checkDBError($stmt);

        return $stmt->fetch();
    }

    public function delete(string $table, array $where)
    {
        $whereMask = $this->getWhereMask($where);
        $sql = sprintf('DELETE FROM %s WHERE %s', $table, $whereMask);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $where['key'] => $where['value'],
        ]);
        ErrorModel::checkDBError($stmt);

        return true;
    }

    public function insert(string $table, array $props)
    {
        $columns = sprintf('(%s)', implode(', ', array_keys($props)));
        $masks = sprintf('(:%s)', implode(', :', array_keys($props)));
        $sql = sprintf('INSERT INTO %s %s VALUES %s', $table, $columns, $masks);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($props);
        ErrorModel::checkDBError($stmt);

        return $this->pdo->lastInsertId();
    }

    public function update(string $table, array $props, array $where)
    {
        $params = [];
        foreach ($props as $key => $value) {
            $params[] = "{$key}=:{$key}";
        }
        $params = implode(', ', $params);

        $whereMask = $this->getWhereMask($where);

        $sql = sprintf('UPDATE %s SET %s WHERE %s', $table, $params, $whereMask);
        $props[$where['key']] = $where['value'];

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($props);
        ErrorModel::checkDBError($stmt);

        return $this->pdo->lastInsertId();
    }

    private function getWhereMask(array $where)
    {
        return sprintf('%s=:%s', $where['column'], $where['key']);
    }
}