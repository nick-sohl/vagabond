<?php

namespace Nick\FlightBookingSystem\Application\Port;

use App\Domain\Entity\Flight;

interface FlightRepository
{
    /**
      * @return array
      */
    public function findAll(): array;

    /**
      * @return Flight
      */
    public function findById(int $id): ?Flight;
}
