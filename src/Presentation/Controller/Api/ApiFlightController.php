<?php

namespace FlightBookingSystem\Presentation\Controller\Api;

use FlightBookingSystem\Application\Service\AirlineService;
use FlightBookingSystem\Application\Service\AirportService;
use FlightBookingSystem\Application\Service\FlightService;
use FlightBookingSystem\Presentation\Utility\Json;

// read-only endpoints. used by the mobile app for the search screen
// (list flights with filters + dropdowns for airlines/airports)
class ApiFlightController
{
    private FlightService $flightService;
    private AirlineService $airlineService;
    private AirportService $airportService;

    private const DEFAULT_PER_PAGE = 20;
    private const MAX_PER_PAGE = 100;

    public function __construct(FlightService $flightService, AirlineService $airlineService, AirportService $airportService)
    {
        $this->flightService = $flightService;
        $this->airlineService = $airlineService;
        $this->airportService = $airportService;
    }

    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = (int) ($_GET['per_page'] ?? self::DEFAULT_PER_PAGE);
        $perPage = max(1, min(self::MAX_PER_PAGE, $perPage));
        $offset = ($page - 1) * $perPage;

        // same filter keys as the web FlightController -> we use the
        // exact same findByFilters() in the repo, no duplication
        $filters = array_filter([
            'departure'    => $_GET['departure'] ?? '',
            'arrival'      => $_GET['arrival'] ?? '',
            'depart_date'  => $_GET['depart_date'] ?? '',
            'depart_time'  => $_GET['depart_time'] ?? '',
            'airline'      => $_GET['airline'] ?? '',
            'availability' => $_GET['availability'] ?? '',
            'max_price'    => $_GET['max_price'] ?? '',
            'country'      => $_GET['country'] ?? '',
        ], fn ($v) => $v !== '');

        if ($filters) {
            $flights = $this->flightService->search($filters, $perPage, $offset);
            $total = $this->flightService->countByFilters($filters);
        } else {
            $flights = $this->flightService->findAll($perPage, $offset);
            $total = $this->flightService->countAll();
        }

        Json::send([
            'data' => array_map([$this, 'presentFlight'], $flights),
            'pagination' => [
                'page'        => $page,
                'per_page'    => $perPage,
                'total'       => $total,
                'total_pages' => (int) max(1, ceil($total / $perPage)),
            ],
        ]);
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            Json::error('Missing flight id.', 400);
            return;
        }
        $flight = $this->flightService->findById($id);
        if (!$flight) {
            Json::error('Flight not found.', 404);
            return;
        }
        Json::send(['data' => $this->presentFlight($flight)]);
    }

    public function airlines(): void
    {
        $airlines = array_map(fn ($row) => [
            'id'        => (int) $row['id'],
            'iata_code' => $row['iata_code'],
            'name'      => $row['name'],
        ], $this->airlineService->findAll());

        Json::send(['data' => $airlines]);
    }

    public function airports(): void
    {
        $q = trim((string) ($_GET['q'] ?? ''));
        $rows = $q === ''
            ? $this->airportService->findAll()
            : $this->airportService->findByName($q);

        $airports = array_map(fn ($row) => [
            'id'        => (int) $row['id'],
            'iata_code' => $row['iata_code'],
            'city'      => $row['city'] ?? null,
            'name'      => $row['name'] ?? null,
            'country'   => $row['country'] ?? null,
        ], $rows);

        Json::send(['data' => $airports]);
    }

    private function presentFlight(array $row): array
    {
        return [
            'id'              => (int) $row['id'],
            'flight_number'   => $row['flight_number'],
            'departure_time'  => $row['departure_time'],
            'arrival_time'    => $row['arrival_time'],
            'price'           => (float) $row['price'],
            'available_seats' => (int) $row['available_seats'],
            'total_seats'     => (int) $row['total_seats'],
            'departure' => [
                'iata'    => $row['dep_iata'],
                'city'    => $row['dep_city'],
                'country' => $row['dep_country'] ?? null,
            ],
            'arrival' => [
                'iata'    => $row['arr_iata'],
                'city'    => $row['arr_city'],
                'country' => $row['arr_country'] ?? null,
            ],
            'airline' => [
                'iata' => $row['airline_iata'],
                'name' => $row['airline_name'],
            ],
            'airplane' => [
                'model'        => $row['airplane_model'] ?? null,
                'manufacturer' => $row['airplane_manufacturer'] ?? null,
            ],
        ];
    }
}
