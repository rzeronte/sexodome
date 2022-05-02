<?php

namespace Tests\Unit;

use Tests\TestCase;
use Sexodome\SexodomeTube\Application\getCategoriesService;

class getCategoriesServiceTest extends TestCase
{
    public function testBadSiteRequest()
    {
        $service = new getCategoriesService();
        $result = $service->execute(879442938492, 1, 1, 1);
        $this->assertFalse($result['status']);
    }

    public function testZeroPageRequest()
    {
        $service = new getCategoriesService();
        $result = $service->execute(env('DEMO_SITE_ID'), 1, 1, 0);
        $this->assertFalse($result['status']);
    }

    public function testZeroPerPageRequest()
    {
        $service = new getCategoriesService();
        $result = $service->execute(env('DEMO_SITE_ID'), 1, 0, 1);
        $this->assertFalse($result['status']);
    }

    public function testLetterInPage()
    {
        $service = new getCategoriesService();
        $result = $service->execute(env('DEMO_SITE_ID'), 1, 10, 'a');
        $this->assertFalse($result['status']);
    }

    public function testInvalidLanguageRequest()
    {
        $service = new getCategoriesService();
        $result = $service->execute(env('DEMO_SITE_ID'), 131233123, 10, 1);
        $this->assertFalse($result['status']);
    }
}
