<?php
namespace App\core;

use App\core\Database;
use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find($id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function all(array $where = [], string $order = ''): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        if ($where) {
            $clauses = [];
            foreach ($where as $k => $v) {
                $clauses[] = "$k = :$k";
                $params[$k] = $v;
            }
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }
        if ($order) {
            $sql .= ' ORDER BY ' . $order;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $keys = array_keys($data);
        $cols = implode(',', $keys);
        $placeholders = implode(',', array_map(fn($k) => ':' . $k, $keys));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$cols}) VALUES ({$placeholders})");
        $stmt->execute($data);
        return (int)$this->db->lastInsertId();
    }

    public function update($id, array $data): bool
    {
        $pairs = implode(',', array_map(fn($k) => "$k = :$k", array_keys($data)));
        $data[$this->primaryKey] = $id;
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$pairs} WHERE {$this->primaryKey} = :{$this->primaryKey}");
        return $stmt->execute($data);
    }

    public function delete($id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $id]);
    }
}
