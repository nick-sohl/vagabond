<?php

namespace FlightBookingSystem\Application\Port;

use FlightBookingSystem\Domain\Entity\Airport;

interface AirportRepository
{
    /**
     * @return array
     */
    public function findAll(): array;

    public function findById(int $id): ?Airport;

    /**
     * @return array
     */
    public function findByName(string $query): array;
}
