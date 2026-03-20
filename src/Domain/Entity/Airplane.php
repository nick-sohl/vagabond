<?php

namespace FlightBookingSystem\Domain\Entity;

use FlightBookingSystem\Domain\Enum\Airplane\Manufacturer;
use FlightBookingSystem\Domain\Enum\Airplane\Range;

class Airplane
{
    public string $name;
    public string $model;
    public Manufacturer $manufacturer;
    public int $capacity;
    public Range $range;
}
