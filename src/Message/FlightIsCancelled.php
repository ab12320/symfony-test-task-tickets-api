<?php

namespace App\Message;

class FlightIsCancelled
{
    private int $flightId;

    public function __construct(int $flightId)
    {
        $this->flightId = $flightId;
    }

    public function getFlightId(): int
    {
        return $this->flightId;
    }
}
