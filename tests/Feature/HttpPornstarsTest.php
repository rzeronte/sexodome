<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Model\Site;

class HttpPornstarsTest extends TestCase
{
    public function testCheckStatusCode()
    {
        $site = Site::find(env('DEMO_SITE_ID'))->first();
        $response = $this->get("/" . $site->pornstars_url);
        $response->assertStatus(200);
    }
}