<?php

namespace FlightBookingSystem\Presentation\Controller;

use FlightBookingSystem\Application\Service\AirportService;

class AirportController
{
    private AirportService $airportService;

    public function __construct(AirportService $airportService)
    {
        $this->airportService = $airportService;
    }

    /**
     * @return array
     */
    public function airports(): array
    {
        return $this->airportService->findAll();
    }
}
