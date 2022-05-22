<?php

namespace App\Http\Controllers;

use App\Services\AffiliatesService;
use Illuminate\Http\Request;

class AffiliatesController extends Controller
{
    public function index(Request $request)
    {
        $kmLimit = config('affiliates.distance_limit_default');

        $affiliatesService = new AffiliatesService();
        $affiliatesService->setReferencePoint(
            config('affiliates.reference_point.latitude'),
            config('affiliates.reference_point.longitude')
        );

        $filtered = $affiliatesService->filterByDistance($kmLimit);

        return view('affiliates.index')->with([
            'affiliates' => $filtered,
            'kmLimit' => $kmLimit
        ]);
    }
}
