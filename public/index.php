<?php

session_start();

use FlightBookingSystem\Application\Service\AirlineService;
use FlightBookingSystem\Application\Service\AirportService;
use FlightBookingSystem\Application\Service\AuthService;
use FlightBookingSystem\Application\Service\BookingService;
use FlightBookingSystem\Application\Service\FlightService;
use FlightBookingSystem\Infrastructure\Repository\SqliteAirlineRepository;
use FlightBookingSystem\Infrastructure\Repository\SqliteAirportRepository;
use FlightBookingSystem\Infrastructure\Repository\SqliteBookingRepository;
use FlightBookingSystem\Infrastructure\Repository\SqliteFlightRepository;
use FlightBookingSystem\Infrastructure\Repository\SqliteUserRepository;
use FlightBookingSystem\Presentation\Controller\AccountController;
use FlightBookingSystem\Presentation\Controller\AirportController;
use FlightBookingSystem\Presentation\Controller\AuthController;
use FlightBookingSystem\Presentation\Controller\BookingController;
use FlightBookingSystem\Presentation\Controller\FlightController;
use FlightBookingSystem\Presentation\Controller\HomeController;
use FlightBookingSystem\Presentation\Controller\PresentationController;
use FlightBookingSystem\Presentation\Router\Router;

// Composer autoload -> Autoload all Classes
require __DIR__ . '/../vendor/autoload.php';

// 1. Build Adapters
$sqliteFlightRepo = new SqliteFlightRepository();
$sqliteAirportRepo = new SqliteAirportRepository();
$sqliteAirlineRepo = new SqliteAirlineRepository();
$sqliteUserRepo = new SqliteUserRepository();
$sqliteBookingRepo = new SqliteBookingRepository();

// 2. Build Services and inject Adapters
$flightService = new FlightService($sqliteFlightRepo);
$airportService = new AirportService($sqliteAirportRepo);
$airlineService = new AirlineService($sqliteAirlineRepo);
$authService = new AuthService($sqliteUserRepo);
$bookingService = new BookingService($sqliteBookingRepo, $sqliteFlightRepo);

// 3. Build Controller and inject Services
$homeController = new HomeController($airlineService);
$flightController = new FlightController($flightService, $airlineService, $bookingService);
$bookingsController = new BookingController($bookingService);
$airportController = new AirportController($airportService);
$authController = new AuthController($authService);
$accountController = new AccountController($authService);
$presentationController = new PresentationController();

// 4. Register routes with instances
$router = new Router();

// Page endpoints (render full views)
$router->get('/', [$homeController, 'index']);
$router->get('/flights', [$flightController, 'index']);
$router->get('/bookings', [$bookingsController, 'index']);
$router->get('/flights/book', [$flightController, 'book']);
$router->post('/flights/book', [$flightController, 'processBooking']);
$router->get('/flights/book/success', [$flightController, 'bookingSuccess']);

// Account endpoints
$router->get('/account', [$accountController, 'index']);
$router->post('/account/profile', [$accountController, 'updateProfile']);
$router->post('/account/password', [$accountController, 'changePassword']);
$router->post('/account/delete', [$accountController, 'deleteAccount']);

// Auth endpoints
$router->get('/auth/login', [$authController, 'loginPage']);
$router->get('/auth/register', [$authController, 'registerPage']);
$router->post('/auth/login', [$authController, 'login']);
$router->post('/auth/register', [$authController, 'register']);
$router->get('/auth/logout', [$authController, 'logout']);

// Presentation
$router->get('/presentation', [$presentationController, 'index']);

// Resource endpoints (render fragments for HTMX components)
$router->get('/api/airports', [$airportController, 'airports']);

// Fetch method and path from request and invoke dispatch method of Router class
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
