<?php

namespace FlightBookingSystem\Presentation\Controller;

use FlightBookingSystem\Presentation\Utility\Htmx;

class HomeController
{
    public function index(): void
    {
        // Init new View with page (e.g. /flights), view (e.g. index) and variables
        // we use the extract method to convert assoc array key/values to variables that contain the value
        $view = new \FlightBookingSystem\Presentation\View\View('home', 'index', ['title' => "Let's Fucking Go!"]);
        // Include the view with the data
        if (Htmx::isHtmxRequest()) {
            $view->render(); // only the view
        } else {
            $view->renderFull(); // view wrapped in base layout
        }
    }
}
