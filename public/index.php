<?php

use FlightBookingSystem\Application\Service\AirportService;
use FlightBookingSystem\Application\Service\FlightService;
use FlightBookingSystem\Infrastructure\Repository\SqliteAirportRepository;
use FlightBookingSystem\Infrastructure\Repository\SqliteFlightRepository;
use FlightBookingSystem\Presentation\Controller\AirportController;
use FlightBookingSystem\Presentation\Controller\BookingController;
use FlightBookingSystem\Presentation\Controller\FlightController;
use FlightBookingSystem\Presentation\Controller\HomeController;
use FlightBookingSystem\Presentation\Router\Router;

// Composer autoload -> Autoload all Classes
require __DIR__ . '/../vendor/autoload.php';

// 1. Build Adapters
$sqliteFlightRepo = new SqliteFlightRepository();
$sqliteAirportRepo = new SqliteAirportRepository();

// 2. Build Services and inject Adapters
$flightService = new FlightService($sqliteFlightRepo);
$airportService = new AirportService($sqliteAirportRepo);

// 3. Build Controller and inject Services
$homeController = new HomeController();
$flightController = new FlightController($flightService);
$bookingsController = new BookingController();
$airportController = new AirportController($airportService);

// 4. Register routes with instances
$router = new Router();

// Page endpoints (render full views)
$router->get('/', [$homeController, 'index']);
$router->get('/flights', [$flightController, 'index']);
$router->get('/bookings', [$bookingsController, 'index']);

// Resource endpoints (render fragments for HTMX components)
$router->get('/api/airports', [$airportController, 'airports']);

// Fetch method and path from request and invoke dispatch method of Router class
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
