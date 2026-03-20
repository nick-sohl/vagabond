<?php

namespace FlightBookingSystem\Presentation\Controller;

use FlightBookingSystem\Application\Service\FlightService;
use FlightBookingSystem\Presentation\Utility\Htmx;
use FlightBookingSystem\Presentation\View\View;

class FlightController
{
    private FlightService $flightService;

    public function __construct(FlightService $flightService)
    {
        $this->flightService = $flightService;
    }

    public function index(): void
    {
        $flights = $this->flightService->findAll();

        $view = new View('flights', 'index', ['flights' => $flights]);

        if (Htmx::isHtmxRequest()) {
            $view->render();
        } else {
            $view->renderFull();
        }
    }
}
