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
     * @return void
     */
    public function airports(): void
    {
        // Determine which input triggered the search
        $targetInput = isset($_GET['departure']) ? 'departure' : (isset($_GET['arrival']) ? 'arrival' : null);
        $query = trim($_GET['departure'] ?? $_GET['arrival'] ?? '');

        // Strip IATA prefix if user typed "ZRH — ..."
        if (str_contains($query, '—')) {
            $query = trim(explode('—', $query)[0]);
        }

        if ($query === '' || $targetInput === null) {
            return;
        }
        $airports = $this->airportService->findByName($query);
        $resultId = $targetInput . '-results';
        include __DIR__ . '/../../../views/components/airport-results.php';
    }
}
