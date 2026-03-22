<?php

namespace FlightBookingSystem\Presentation\Controller;

use FlightBookingSystem\Application\Service\AirlineService;
use FlightBookingSystem\Presentation\Utility\Htmx;
use FlightBookingSystem\Presentation\View\View;

class HomeController
{
    private AirlineService $airlineService;

    public function __construct(AirlineService $airlineService)
    {
        $this->airlineService = $airlineService;
    }

    public function index(): void
    {
        $airlines = $this->airlineService->findAll();

        $view = new View('home', 'index', [
            'airlines' => $airlines,
        ]);

        if (Htmx::isHtmxRequest()) {
            $view->render();
        } else {
            $view->renderFull();
        }
    }
}
