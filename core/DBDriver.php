<?php

namespace core;

use core\Exception\DBException;
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
        $this->checkDBError($stmt);

        return $stmt->fetchAll();
    }

    public function selectOne(string $table, string $where, array $where_props)
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s', $table, $where);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($where_props);
        $this->checkDBError($stmt);

        $data = $stmt->fetch();

        if (!$data) {
            throw new DBException('Не найдено');
        }

        return $data;
    }

    public function delete(string $table, string $where, array $where_props)
    {
        $sql = sprintf('DELETE FROM %s WHERE %s', $table, $where);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($where_props);
        $this->checkDBError($stmt);

        return true;
    }

    public function insert(string $table, array $props)
    {
        $columns = sprintf('(%s)', implode(', ', array_keys($props)));
        $masks = sprintf('(:%s)', implode(', :', array_keys($props)));
        $sql = sprintf('INSERT INTO %s %s VALUES %s', $table, $columns, $masks);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($props);
        $this->checkDBError($stmt);

        return $this->pdo->lastInsertId();
    }

    public function update(string $table, array $props, string $where, array $where_props)
    {
        $params = [];
        foreach ($props as $key => $value) {
            $params[] = "{$key}=:{$key}";
        }
        $params = implode(', ', $params);

        $sql = sprintf('UPDATE %s SET %s WHERE %s', $table, $params, $where);
        $props = array_merge($props, $where_props);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($props);
        $this->checkDBError($stmt);

        return $this->pdo->lastInsertId();
    }

    public function runSql(string $sql, array $props)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($props);
        $this->checkDBError($stmt);

        $data = $stmt->fetch();

        if (!$data) {
            throw new DBException('Не найдено');
        }

        return $data;
    }

    private function checkDBError($stmt)
    {
        $error = $stmt->errorInfo();
        if ($error[0] != \PDO::ERR_NONE) {
            throw new DBException("Ошибка БД: $error[2]");
        }
    }
}