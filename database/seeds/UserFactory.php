<?php
declare(strict_types=1);

namespace database\seeds;

require 'database/bootstrap/autoload.php';

use commands\Database;
use PDOException;

class UserFactory
{
    private Database|null $db;

    public function __construct()
    {
        $this->db = Database::getInstance(
            "$_ENV[DB_DSN]",
            "$_ENV[DB_USERNAME]",
            "$_ENV[DB_PASSWORD]");
    }

    public function seed(string $table)
    {
        try {
            $emails = [];
            for ($i = 0; $i < 3; $i++) {
                $emails[] = $this->generateRandomEmail();
            }

            $query = "INSERT INTO $table (name, email, gender, status)
                VALUES ('John Doe', '$emails[0]' , 'male', 'active'),
                       ('Jane Doe', '$emails[1]' , 'female', 'active'),
                       ('John Smith', '$emails[2]' , 'male', 'inactive')";
            $this->db->execute($query);
            echo "Data seeded successfully\n";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
        $this->db = null;
    }

    public function generateRandomEmail($length = 10): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString . '@inn.com';
    }
}

$seed = new UserFactory();
$seed->seed($argv[1] ?? 'users');
