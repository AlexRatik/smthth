<?php
declare(strict_types=1);

namespace models;

use commands\Database;

class User
{
    private Database $database;
    private string $table;

    public function __construct()
    {
        $this->setDatabase(Database::getInstance(
            "$_ENV[DB_DSN]",
            "$_ENV[DB_USERNAME]",
            "$_ENV[DB_PASSWORD]"
        ));
        $this->table = 'users';
    }

    public function setDatabase(Database $database): void
    {
        $this->database = $database;
    }

    public function getOne(int $id): array
    {
        $user = $this->database->getOne($this->table, $id);
        if ($user) {
            return [$user, 200];
        } else {
            return [['error' => "There is no user with id $id"], 404];
        }
    }

    public function getRecordsWithLimitAndOffset(int $pageNumber, int $pageLimit): array
    {
        $offset = ($pageNumber - 1) * $pageLimit;

        $data = $this->database->getRecordsWithLimitAndOffset($pageLimit, $offset, $this->table);

        if (count($data)) {
            return [$data, 200];
        } else {
            return [['error' => 'There are no more records to load'], 404];
        }
    }

    public function create(array $params): array
    {
        $this->database->insert($this->table, $params);
        return [$params, 201];
    }

    public function update(int $id, array $params): array
    {
        $this->database->update($this->table, $id, $params);
        $params['id'] = $id;
        return [$params, 200];
    }

    public function delete(int $id): array
    {
        $user = $this->database->getOne($this->table, $id);
        if ($user) {
            $this->database->delete($this->table, $id);
            return [[], 204];
        } else {
            return [["error" => "There is no user with id $id"], 404];
        }
    }

    public function deleteMultiple(array $ids): array
    {
        if (empty($ids)) {
            return [['error' => 'Invalid input'], 400];
        } else {
            $stmt = $this->database->deleteMultiple($this->table, $ids);
            if (!$stmt->rowCount()) {
                return [['error' => 'User(s) not found'], 404];
            } else {
                return [[], 204];
            }
        }
    }

    public function countAllRecords(): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->database->countAllRecords($this->table));
    }
}