<?php

namespace Tests\Feature;

use App\Services\AffiliatesService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AffiliatesTest extends TestCase
{
    public function testFilteringDistance()
    {
        $affiliateService = new AffiliatesService();
        $this->assertIsArray($affiliateService->filterByDistance());
        $this->assertIsArray($affiliateService->filterByDistance(1000));
        $this->assertIsArray($affiliateService->filterByDistance(1));
    }
}
