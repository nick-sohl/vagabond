<?php

namespace FlightBookingSystem\Infrastructure\Repository;

use FlightBookingSystem\Application\Port\AirlineRepository;
use FlightBookingSystem\Infrastructure\Database\Database;

class SqliteAirlineRepository implements AirlineRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findAll(): array
    {
        $statement = $this->pdo->query('SELECT id, iata_code, name FROM airlines ORDER BY name');

        return $statement->fetchAll();
    }
}
