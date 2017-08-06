<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Model\Site;
use Goutte\Client;

class HttpPornstarsTest extends TestCase
{
    public function testCheckStatusCode()
    {
        $site = Site::find(env('DEMO_SITE_ID'))->first();
        $response = $this->get("/" . $site->pornstars_url);
        $response->assertStatus(200);
    }

    public function testCheckForH1()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();

        $url = 'http://' . $site->getHost() . "/" . $site->pornstars_url;
        $crawler = $client->request('GET', $url);
        $h1_count = $crawler->filter('h1')->count();

        $this->assertTrue($h1_count == 1 ? true : false);
    }
}