<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Front\getCategoriesService;

class getCategoriesServiceTest extends TestCase
{
    public function testBadSiteRequest()
    {
        $service = new getCategoriesService();

        $result = $service->execute(
            879442938492,
            1,
            1,
            1
        );

        $this->assertFalse($result['status']);
    }

    public function testZeroPageRequest()
    {
        $service = new getCategoriesService();

        $result = $service->execute(
            1,
            1,
            1,
            0
        );

        $this->assertFalse($result['status']);
    }

    public function testZeroPerPageRequest()
    {
        $service = new getCategoriesService();

        $result = $service->execute(
            1,
            1,
            0,
            1
        );

        $this->assertFalse($result['status']);
    }

    public function testInvalidLanguageRequest()
    {
        $service = new getCategoriesService();

        $result = $service->execute(
            47,
            131233123,
            10,
            1
        );

        $this->assertFalse($result['status']);
    }
}
