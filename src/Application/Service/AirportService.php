<?php

namespace FlightBookingSystem\Application\Service;

use FlightBookingSystem\Infrastructure\Repository\SqliteAirportRepository;

class AirportService
{
    private SqliteAirportRepository $sqliteAirportRepository;

    public function __construct(SqliteAirportRepository $sqliteAirportRepository)
    {
        $this->sqliteAirportRepository = $sqliteAirportRepository;
    }

    public function findAll(): array
    {
        return $this->sqliteAirportRepository->findAll();
    }
}
