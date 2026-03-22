<?php

namespace FlightBookingSystem\Application\Service;

use FlightBookingSystem\Application\Port\AirlineRepository;

class AirlineService
{
    private AirlineRepository $airlineRepository;

    public function __construct(AirlineRepository $airlineRepository)
    {
        $this->airlineRepository = $airlineRepository;
    }

    public function findAll(): array
    {
        return $this->airlineRepository->findAll();
    }
}
