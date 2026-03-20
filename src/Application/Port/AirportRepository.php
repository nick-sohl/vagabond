<?php

namespace FlightBookingSystem\Application\Port;

use FlightBookingSystem\Domain\Entity\Airport;

interface AirportRepository
{
    public function findAll(): array;

    public function findById(int $id): ?Airport;
}
