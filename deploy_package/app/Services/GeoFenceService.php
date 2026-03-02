<?php

namespace App\Services;

class GeoFenceService
{
    public function distanceMeters(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function isWithinRadius(
        float $sourceLat,
        float $sourceLon,
        float $targetLat,
        float $targetLon,
        int $allowedRadiusMeters
    ): bool {
        return $this->distanceMeters($sourceLat, $sourceLon, $targetLat, $targetLon) <= $allowedRadiusMeters;
    }
}
