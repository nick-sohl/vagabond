<?php

namespace FlightBookingSystem\Presentation\Utility;

class Htmx
{
    public static function isHtmxRequest(): bool
    {
        return isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true';
    }

    public static function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function isDelete(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }

    public static function isPut(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }
}
