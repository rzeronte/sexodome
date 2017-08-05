<?php

namespace Tests\Feature;

use App\Services\Front\getSearchService;
use Tests\TestCase;

class getSearchServiceTest extends TestCase
{
    public function testGetEmptyQuerystring()
    {
        $service = new getSearchService();
        $result = $service->execute("", 47, 1, 10);
        $this->assertFalse($result['status']);
    }

    public function testFalseQueryString()
    {
        $service = new getSearchService();
        $result = $service->execute(false, 10, 20, 10);
        $this->assertFalse($result['status']);
    }

    public function testBadSiteRequest()
    {
        $service = new getSearchService();
        $result = $service->execute("anal", 4131231237, 1, 10);
        $this->assertFalse($result['status']);
    }

    public function testBadLanguageRequest()
    {
        $service = new getSearchService();
        $result = $service->execute("anal", env('DEMO_SITE_ID'), 1312312323, 10);
        $this->assertFalse($result['status']);
    }

    public function testBadPerPageRequest()
    {
        $service = new getSearchService();
        $result = $service->execute("anal", env('DEMO_SITE_ID'), 1, 0);
        $this->assertFalse($result['status']);
    }

    public function testSuccessRequest()
    {
        $service = new getSearchService();
        $result = $service->execute("anal", env('DEMO_SITE_ID'), 1, 10);
        $this->assertTrue($result['status']);
    }
}
