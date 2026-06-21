<?php

namespace FlightBookingSystem\Presentation\Controller\Api;

use FlightBookingSystem\Application\Port\ApiTokenRepository;
use FlightBookingSystem\Application\Service\BookingService;
use FlightBookingSystem\Presentation\Utility\Json;

// booking endpoints for the mobile app.
// EVERY route here needs a token.
// !!! never read user_id from the body, always from the token !!!
class ApiBookingController
{
    private BookingService $bookingService;
    private ApiTokenRepository $tokenRepository;

    public function __construct(BookingService $bookingService, ApiTokenRepository $tokenRepository)
    {
        $this->bookingService = $bookingService;
        $this->tokenRepository = $tokenRepository;
    }

    public function index(): void
    {
        $userId = ApiAuth::requireUser($this->tokenRepository);
        if ($userId === null) {
            return;
        }

        $bookings = array_map([$this, 'presentBooking'], $this->bookingService->getByUserId($userId));

        // also filter on the server (epic 3 wants airline + price filter).
        // the mobile UI does it too, but doing it here means less data over the wire
        $airline = $_GET['airline'] ?? '';
        $maxPrice = $_GET['max_price'] ?? '';

        if ($airline !== '') {
            $bookings = array_values(array_filter($bookings, fn ($b) => $b['flight']['airline']['iata'] === $airline));
        }
        if ($maxPrice !== '' && is_numeric($maxPrice)) {
            $cap = (float) $maxPrice;
            $bookings = array_values(array_filter($bookings, fn ($b) => $b['total_price'] <= $cap));
        }

        Json::send(['data' => $bookings]);
    }

    public function create(): void
    {
        $userId = ApiAuth::requireUser($this->tokenRepository);
        if ($userId === null) {
            return;
        }

        $body = Json::body();
        $flightId = (int) ($body['flight_id'] ?? 0);
        $numTickets = max(1, (int) ($body['num_tickets'] ?? 1));

        if ($flightId <= 0) {
            Json::error('Missing flight_id.', 400);
            return;
        }

        $result = $this->bookingService->createBooking($userId, $flightId, $numTickets);
        if (!$result['success']) {
            Json::error($result['error'], 422);
            return;
        }

        Json::send([
            'booking_id' => (int) $result['booking_id'],
            'success'    => true,
        ], 201);
    }

    private function presentBooking(array $row): array
    {
        return [
            'id'           => (int) $row['booking_id'],
            'booking_date' => $row['booking_date'],
            'num_tickets'  => (int) $row['num_tickets'],
            'total_price'  => (float) $row['total_price'],
            'status'       => $row['status'],
            'flight' => [
                'id'             => (int) $row['flight_id'],
                'flight_number'  => $row['flight_number'],
                'departure_time' => $row['departure_time'],
                'arrival_time'   => $row['arrival_time'],
                'price'          => (float) $row['price'],
                'departure' => [
                    'iata' => $row['dep_iata'],
                    'city' => $row['dep_city'],
                ],
                'arrival' => [
                    'iata' => $row['arr_iata'],
                    'city' => $row['arr_city'],
                ],
                'airline' => [
                    'iata' => $row['airline_iata'],
                    'name' => $row['airline_name'],
                ],
            ],
        ];
    }
}
