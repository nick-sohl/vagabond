<?php

namespace FlightBookingSystem\Application\Service;

use FlightBookingSystem\Infrastructure\Repository\SqliteFlightRepository;

class FlightService
{
    private SqliteFlightRepository $sqliteFlightRepository;

    public function __construct(SqliteFlightRepository $sqliteFlightRepository)
    {
        $this->sqliteFlightRepository = $sqliteFlightRepository;
    }

    public function findAll(): array
    {
        return $this->sqliteFlightRepository->findAll();
    }
}
