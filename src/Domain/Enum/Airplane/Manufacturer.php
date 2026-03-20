<?php

namespace FlightBookingSystem\Domain\Enum\Airplane;

enum Manufacturer : string
{
    case AIRBUS = "Airbus";
    case BOEING = "Boeing";
    case EMBRAER = "Embraer";
}
