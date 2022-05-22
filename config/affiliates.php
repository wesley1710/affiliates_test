<?php

return [
    'reference_point' => [
        'latitude' => env('REFERENCE_POINT_LATITUDE', 53.3340285),
        'longitude' => env('REFERENCE_POINT_LONGITUDE', -6.2535495)
    ],
    'distance_limit_default' => env('DISTANCE_LIMIT_DEFAULT', 100)
];
