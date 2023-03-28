<?php
declare(strict_types=1);

namespace commands;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static ?Database $instance = null;

    private PDO $pdo;

    private function __construct(string $dsn, string $username, string $password)
    {
        $this->connect($dsn, $username, $password);
    }

    public function __wakeup()
    {
        throw new Exception("Unserializing of the singleton instance is not allowed.");
    }

    private function __clone()
    {
        throw new Exception("Cloning of the singleton instance is not allowed.");
    }

    public static function getInstance(string $dsn, string $username, string $password): Database
    {
        if (self::$instance === null) {
            self::$instance = new self($dsn, $username, $password);
        }
        return self::$instance;
    }

    private function connect(string $dsn, string $username, string $password): void
    {
        try {
            $this->pdo = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function execute(string $query, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);

            return $stmt;
        } catch (PDOException $e) {
            http_response_code(intval($e->getCode()));
            die(json_encode(['error' => $e->getMessage()]));
        }
    }

    public function getOne(string $table, int $id): array|bool
    {
        $query = "SELECT * FROM $table WHERE id = :id";
        $params = [':id' => $id];

        return $this->execute($query, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecordsWithLimitAndOffset(int $limit, int $offset, string $table): array
    {
        $query = "SELECT * FROM $table LIMIT $limit OFFSET $offset";

        return $this->execute($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(string $table, array $params): array|bool
    {
        $keys = array_keys($params);
        $query = sprintf("INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(", ", $keys),
            ":" . implode(", :", $keys)
        );

        return $this->execute($query, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public function delete(string $table, int $id): PDOStatement
    {
        $query = "DELETE FROM $table WHERE id = :id";
        $params = [':id' => $id];

        return $this->execute($query, $params);
    }

    public function deleteMultiple(string $table, array $ids): PDOStatement
    {
        $query = "DELETE FROM $table WHERE id IN (" . implode(',', $ids) . ")";

        return $this->execute($query);
    }

    public function update(string $table, int $id, array $params): PDOStatement
    {
        $queryData = null;

        foreach ($params as $key => $value) {
            $queryData = $queryData . "$key = :$key, ";
        }

        $queryData = rtrim($queryData, ', ');
        $query = "UPDATE $table SET $queryData WHERE id = $id";

        return $this->execute($query, $params);
    }

    public function countAllRecords(string $table): int
    {
        $query = "SELECT COUNT(*) as total FROM $table";

        return $this->execute($query)->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
