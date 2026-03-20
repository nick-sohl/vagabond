<?php

namespace FlightBookingSystem\Domain\Entity;

class Airport
{
    // identification
    private string $iataCode;
    private string $name;

    // location
    private float $latitude;
    private float $longitude;
    private string $city;
    private string $state;             // State / province / region
    private string $country;           // ISO 3166-1 alpha-2, e.g. "US"
    private string $timezone;          // IANA timezone, e.g. "America/New_York"
    private float $utcOffset;          // Hours from UTC

    public function __construct(
        string $iataCode,
        string $name,
        float $latitude,
        float $longitude,
        string $city,
        string $state,
        string $country,
        string $timezone,
        float $utcOffset,
    ) {
        $this->iataCode = strtoupper($iataCode);
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
        $this->timezone = $timezone;
        $this->utcOffset = $utcOffset;
    }
}
