<?php

namespace FlightBookingSystem\Domain\Entity;

use DateTimeImmutable;
use FlightBookingSystem\Domain\Enum\Airplane\Capacity;

class Flight
{
    public int $id;
    public string $flightNumber;
    public int $airlineId;
    public int $airplaneId;
    public string $origin;
    public string $destination;
    public DateTimeImmutable $departureDate {
        set(string $value) {
            $this->departureDate = new DateTimeImmutable($value);
        }
    }
    public DateTimeImmutable $arrivalDate;
    public int $price;
    public int $seats;

    // constructor
    public function __construct(string $origin, string $destination) {}

    // methods
}
