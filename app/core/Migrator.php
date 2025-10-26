<?php
namespace App\core;

use PDO;
use Throwable;

class Migrator
{
    private PDO $db;
    private string $path;

    public function __construct(string $migrationsPath)
    {
        $this->db = Database::getInstance();
        $this->path = rtrim($migrationsPath, DIRECTORY_SEPARATOR);
        $this->ensureMigrationsTable();
    }

    private function ensureMigrationsTable(): void
    {
        $this->db->exec(
            "CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE,
                applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );
    }

    public function applied(): array
    {
        $stmt = $this->db->query("SELECT name FROM migrations ORDER BY id ASC");
        return array_column($stmt->fetchAll(), 'name');
    }

    public function pending(): array
    {
        $files = glob($this->path . DIRECTORY_SEPARATOR . '*.php') ?: [];
        sort($files, SORT_STRING);
        $applied = $this->applied();
        $pending = [];
        foreach ($files as $file) {
            $name = basename($file);
            if (!in_array($name, $applied, true)) {
                $pending[] = $file;
            }
        }
        return $pending;
    }

    public function up(): array
    {
        $results = [];
        foreach ($this->pending() as $file) {
            $name = basename($file);
            try {
                $migration = require $file;
                if (is_callable($migration)) {
                    $migration($this->db);
                } elseif (is_array($migration)) {
                    foreach ($migration as $step) {
                        if (is_callable($step)) { $step($this->db); }
                    }
                }
                $this->db->prepare("INSERT INTO migrations (name) VALUES (:n)")->execute(['n' => $name]);
                $results[] = [ 'name' => $name, 'status' => 'applied' ];
            } catch (Throwable $e) {
                $results[] = [ 'name' => $name, 'status' => 'error', 'error' => $e->getMessage() ];
                break;
            }
        }
        return $results;
    }
}
