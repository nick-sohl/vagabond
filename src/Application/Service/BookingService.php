<?php

namespace FlightBookingSystem\Application\Service;

use FlightBookingSystem\Application\Port\BookingRepository;
use FlightBookingSystem\Application\Port\FlightRepository;

class BookingService
{
    private BookingRepository $bookingRepository;
    private FlightRepository $flightRepository;

    public function __construct(BookingRepository $bookingRepository, FlightRepository $flightRepository)
    {
        $this->bookingRepository = $bookingRepository;
        $this->flightRepository = $flightRepository;
    }

    public function getByUserId(int $userId): array
    {
        return $this->bookingRepository->findByUserId($userId);
    }

    public function createBooking(int $userId, int $flightId, int $numTickets): array
    {
        $flight = $this->flightRepository->findById($flightId);

        if (!$flight) {
            return ['success' => false, 'error' => 'Flight not found.'];
        }

        if ($flight['available_seats'] < $numTickets) {
            return ['success' => false, 'error' => 'Not enough seats available.'];
        }

        $totalPrice = $flight['price'] * $numTickets;

        $bookingId = $this->bookingRepository->create($userId, $flightId, $numTickets, $totalPrice);

        return ['success' => true, 'booking_id' => $bookingId];
    }
}
