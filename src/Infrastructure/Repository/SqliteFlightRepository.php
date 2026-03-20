<?php

namespace FlightBookingSystem\Infrastructure\Repository;

use FlightBookingSystem\Application\Port\FlightRepository;
use FlightBookingSystem\Domain\Entity\Flight;
use FlightBookingSystem\Infrastructure\Database\Database;

// Adapter
class SqliteFlightRepository implements FlightRepository
{
    private \PDO $pdo;

    private string $sql_find_all = 'SELECT * FROM flights;';

    public function __construct()
    {
        // Get DB connection
        $this->pdo = Database::getInstance();
    }

    public function findAll(): array
    {
        $statement = $this->pdo->query($this->sql_find_all);

        return $statement->fetchAll();
    }

    public function findById(int $id): ?Flight {}
}
