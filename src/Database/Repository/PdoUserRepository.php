<?php

namespace App\Database\Repository;

use App\Repository\UserRepository;
use App\Models\User;
use PDO;

class PdoUserRepository implements UserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function add(User $user): User
    {
        $insert_query = "INSERT INTO users (name, email, date_created) VALUES (
            '{$user->name()}',
            '{$user->email()}',
            '{$user->dateCreated()->format('Y-m-d')}'
        );";
        $success = $this->connection->exec($insert_query);

        if ($success) {
            $user->defineId($this->connection->lastInsertId());
        }

        return $user;
    }
}