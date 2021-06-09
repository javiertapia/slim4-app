<?php
declare(strict_types=1);

namespace App\Domain\User\Repositories;

use Illuminate\Database\Connection;

final class UserCreatorRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insertUser(array $user): int
    {
        $row = [
            'username'   => $user['username'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'email'      => $user['email'],
        ];
        return $this->connection->table('users')->insertGetId($row);
    }
}
