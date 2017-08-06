<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Model\Site;
use Goutte\Client;

class HttpPornstarTest extends TestCase
{
    public function testCheckStatusCode()
    {
        $site = Site::find(env('DEMO_SITE_ID'))->first();
        $pornstar = $site->pornstars()->first();
        $uri = '/' . $site->pornstar_url . '/' . $pornstar->permalink;

        $response = $this->get($uri);
        $response->assertStatus(200);
    }

    public function testCheckForH1()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();
        $pornstar = $site->pornstars()->first();
        $uri = '/' . $site->pornstar_url . '/' . $pornstar->permalink;

        $url = 'http://' . $site->getHost() . $uri;
        $crawler = $client->request('GET', $url);
        $h1_count = $crawler->filter('h1')->count();

        $this->assertTrue($h1_count == 1 ? true : false);
    }

    public function testCheckForH2()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();
        $pornstar = $site->pornstars()->first();
        $uri = '/' . $site->pornstar_url . '/' . $pornstar->permalink;

        $url = 'http://' . $site->getHost() . $uri;
        $crawler = $client->request('GET', $url);
        $h1_count = $crawler->filter('h2')->count();

        $this->assertTrue($h1_count == 1 ? true : false);
    }

    public function testCheckNonEmptyTitle()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();
        $pornstar = $site->pornstars()->first();
        $uri = '/' . $site->pornstar_url . '/' . $pornstar->permalink;

        $url = 'http://' . $site->getHost() . $uri;
        $crawler = $client->request('GET', $url);
        $title = $crawler->filter('title')->text();

        $this->assertTrue(strlen($title) > 0 ? true : false);
    }

    public function testCheckNonEmptyDescription()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();
        $pornstar = $site->pornstars()->first();
        $uri = '/' . $site->pornstar_url . '/' . $pornstar->permalink;

        $url = 'http://' . $site->getHost() . $uri;
        $crawler = $client->request('GET', $url);
        $description = $crawler->filterXpath('//meta[@name="description"]')->attr('content');

        $this->assertTrue(strlen($description) > 0 ? true : false);
    }
}