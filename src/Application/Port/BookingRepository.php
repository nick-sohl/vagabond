<?php

namespace FlightBookingSystem\Application\Port;

interface BookingRepository
{
    public function findByUserId(int $userId): array;

    public function create(int $userId, int $flightId, int $numTickets, float $totalPrice): int;
}
