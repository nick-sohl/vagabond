<?php

namespace FlightBookingSystem\Infrastructure\Repository;

use FlightBookingSystem\Application\Port\AirportRepository;
use FlightBookingSystem\Domain\Entity\Airport;
use FlightBookingSystem\Infrastructure\Database\Database;

// Adapter
class SqliteAirportRepository implements AirportRepository
{
    private \PDO $pdo;

    private string $sql_find_all = 'SELECT * FROM airports;';

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

    public function findById(int $id): ?Airport {}
}
