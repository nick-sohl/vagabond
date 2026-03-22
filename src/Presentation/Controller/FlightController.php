<?php

namespace FlightBookingSystem\Presentation\Controller;

use FlightBookingSystem\Application\Service\AirlineService;
use FlightBookingSystem\Application\Service\BookingService;
use FlightBookingSystem\Application\Service\FlightService;
use FlightBookingSystem\Presentation\Utility\Htmx;
use FlightBookingSystem\Presentation\View\View;

class FlightController
{
    private FlightService $flightService;
    private AirlineService $airlineService;
    private BookingService $bookingService;

    public function __construct(FlightService $flightService, AirlineService $airlineService, BookingService $bookingService)
    {
        $this->flightService = $flightService;
        $this->airlineService = $airlineService;
        $this->bookingService = $bookingService;
    }

    private const PER_PAGE = 10;

    public function index(): void
    {
        // Split combined datetime-local into date and optional time
        $departDatetime = trim($_GET['depart_datetime'] ?? '');
        $departDate = '';
        $departTime = '';
        if ($departDatetime !== '') {
            if (str_contains($departDatetime, 'T')) {
                [$departDate, $departTime] = explode('T', $departDatetime, 2);
            } else {
                $departDate = $departDatetime;
            }
        }

        $filters = array_filter([
            'departure'    => $_GET['departure'] ?? '',
            'arrival'      => $_GET['arrival'] ?? '',
            'depart_date'  => $departDate,
            'depart_time'  => $departTime,
            'airline'      => $_GET['airline'] ?? '',
            'availability' => $_GET['availability'] ?? '',
            'max_price'    => $_GET['max_price'] ?? '',
            'country'      => $_GET['country'] ?? '',
        ]);

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * self::PER_PAGE;

        if ($filters) {
            $flights = $this->flightService->search($filters, self::PER_PAGE, $offset);
            $totalFlights = $this->flightService->countByFilters($filters);
        } else {
            $flights = $this->flightService->findAll(self::PER_PAGE, $offset);
            $totalFlights = $this->flightService->countAll();
        }

        $totalPages = max(1, (int) ceil($totalFlights / self::PER_PAGE));
        $airlines = $this->airlineService->findAll();

        $view = new View('flights', 'index', [
            'flights'      => $flights,
            'airlines'     => $airlines,
            'currentPage'  => $page,
            'totalPages'   => $totalPages,
            'totalFlights' => $totalFlights,
        ]);

        if (Htmx::isHtmxRequest()) {
            $view->render();
        } else {
            $view->renderFull();
        }
    }

    public function book(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        $flightId = (int) ($_GET['id'] ?? 0);
        $flight = $this->flightService->findById($flightId);

        if (!$flight) {
            http_response_code(404);
            return;
        }

        $error = null;
        $view = new View('flights', 'detail', [
            'flight' => $flight,
            'error'  => $error,
        ]);

        if (Htmx::isHtmxRequest()) {
            $view->render();
        } else {
            $view->renderFull();
        }
    }

    public function processBooking(): void
    {
        $flightId = (int) ($_POST['flight_id'] ?? 0);
        $numTickets = (int) ($_POST['num_tickets'] ?? 1);

        if (!isset($_SESSION['user_id'])) {
            header('HX-Redirect: /auth/login');
            return;
        }

        $userId = (int) $_SESSION['user_id'];
        $result = $this->bookingService->createBooking($userId, $flightId, $numTickets);

        if ($result['success']) {
            header('HX-Redirect: /flights/book/success?id=' . $result['booking_id']);
            return;
        }

        $flight = $this->flightService->findById($flightId);
        $view = new View('flights', 'detail', [
            'flight' => $flight,
            'error'  => $result['error'],
        ]);
        $view->render();
    }

    public function bookingSuccess(): void
    {
        $view = new View('flights', 'success', []);

        if (Htmx::isHtmxRequest()) {
            $view->render();
        } else {
            $view->renderFull();
        }
    }
}
