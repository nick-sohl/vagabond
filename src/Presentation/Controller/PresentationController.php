<?php

namespace FlightBookingSystem\Presentation\Controller;

class PresentationController
{
    public function index(): void
    {
        include __DIR__ . '/../../../views/templates/presentation.php';
    }
}
