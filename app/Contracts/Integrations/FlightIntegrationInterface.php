<?php

namespace App\Contracts\Integrations;

interface FlightIntegrationInterface
{
    /**
     * Search flights based on generic search parameters.
     *
     * @param array $params
     * @return array
     */
    public function searchFlights(array $params): array;

    /**
     * Search airports matching keyword.
     *
     * @param string $keyword
     * @return array
     */
    public function searchAirports(string $keyword): array;
}
