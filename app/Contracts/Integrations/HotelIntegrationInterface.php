<?php

namespace App\Contracts\Integrations;

interface HotelIntegrationInterface
{
    /**
     * Search hotels based on generic search parameters.
     *
     * @param array $params
     * @return array
     */
    public function searchHotels(array $params): array;
}
