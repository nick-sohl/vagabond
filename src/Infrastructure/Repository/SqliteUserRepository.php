<?php

namespace FlightBookingSystem\Infrastructure\Repository;

use FlightBookingSystem\Application\Port\UserRepository;
use FlightBookingSystem\Infrastructure\Database\Database;

class SqliteUserRepository implements UserRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $statement->bindParam(':email', $email);
        $statement->execute();

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function create(string $firstName, string $lastName, string $email, string $passwordHash): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)'
        );
        $statement->bindParam(':first_name', $firstName);
        $statement->bindParam(':last_name', $lastName);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':password', $passwordHash);
        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, string $firstName, string $lastName, string $email): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email WHERE id = :id'
        );
        $statement->bindParam(':first_name', $firstName);
        $statement->bindParam(':last_name', $lastName);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function updatePassword(int $id, string $passwordHash): void
    {
        $statement = $this->pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
        $statement->bindParam(':password', $passwordHash);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
