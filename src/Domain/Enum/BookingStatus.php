<?php

namespace FlightBookingSystem\Domain\Enum;

enum BookingStatus : string
{
    case CONFIRMED = "confirmed";
    case CANCELLED = "cancelled";
    case PENDING = "pending";
}
