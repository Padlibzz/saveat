<?php

namespace App\Helpers;

class GeoHelper
{
    /**
     * Hitung jarak antara dua koordinat (km) menggunakan formula Haversine
     */
    public static function jarakKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 1);
    }
}