<?php

namespace FlightBookingSystem\Application\Port;

interface AirlineRepository
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array;
}
