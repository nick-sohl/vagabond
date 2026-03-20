<?php

namespace FlightBookingSystem\Domain\Entity;

class Flight
{
    public int $id;
    public string $flightNumber;
    public int $airlineId;
    public int $airplaneId;
    public int $departureAirportId;
    public int $arrivalAirportId;
    public \DateTimeImmutable $departureTime {
        set(\DateTimeImmutable|string $value) {
            $this->departureTime = $value instanceof \DateTimeImmutable
                ? $value
                : new \DateTimeImmutable($value);
        }
    }
    public \DateTimeImmutable $arrivalTime {
        set(\DateTimeImmutable|string $value) {
            $this->arrivalTime = $value instanceof \DateTimeImmutable
                ? $value
                : new \DateTimeImmutable($value);
        }
    }
    public float $price;
    public int $totalSeats;
    public int $availableSeats;
}
