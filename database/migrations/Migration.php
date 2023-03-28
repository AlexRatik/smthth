<?php
declare(strict_types=1);

require 'database/bootstrap/autoload.php';

use database\seeds\UserFactory;
use commands\Database;

class Migration
{
    private Database|null $db;

    private string $table;

    public function __construct(string $table)
    {
        $this->db = Database::getInstance(
            "$_ENV[DB_DSN]",
            "$_ENV[DB_USERNAME]",
            "$_ENV[DB_PASSWORD]");

        $this->table = $table;
    }

    public function migrate(): void
    {
        try {
            $query = "CREATE TABLE $this->table (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        gender ENUM('male', 'female', 'other') NOT NULL,
        status ENUM('active', 'inactive') NOT NULL
      )";

            $this->db->execute($query);
            echo "Table $this->table created successfully\n";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }

        $this->db = null;
    }

    public function seed(): void
    {
        $seed = new UserFactory();
        $seed->seed($this->table);
    }

}

$m = new Migration($argv[1] ?? 'users');
$m->migrate();
$m->seed();
