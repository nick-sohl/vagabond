<?php

namespace FlightBookingSystem\Application\Port;

use FlightBookingSystem\Domain\Entity\Flight;

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
