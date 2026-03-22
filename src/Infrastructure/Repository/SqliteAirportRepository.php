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
    private string $sql_find_by_input = "SELECT id, iata_code, city FROM airports WHERE city LIKE :input OR iata_code LIKE :iata";

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

    public function findByName(string $query): array
    {
        $statement = $this->pdo->prepare($this->sql_find_by_input);
        $likeQuery = '%' . $query . '%';
        $iataQuery = strtoupper($query) . '%';
        $statement->bindParam(':input', $likeQuery);
        $statement->bindParam(':iata', $iataQuery);
        $statement->execute();

        return $statement->fetchAll();
    }
}
