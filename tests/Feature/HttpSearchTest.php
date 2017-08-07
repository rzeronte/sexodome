<?php

namespace Tests\Feature;

use Tests\TestCase;
use Goutte\Client;
use App\Model\Site;

class HttpSearchTest extends TestCase
{
    public function testCheckStatusCode()
    {
        $response = $this->get("/search?q=anal");
        $response->assertStatus(200);
    }

    public function testEmptySearch()
    {
        $response = $this->get("/search?q=");
        $response->assertStatus(404);
    }

    public function testRareCharacteresSearch()
    {
        $response = $this->get("/search?q=*!$%&/(");
        $response->assertStatus(200);
    }

    public function testCheckForH1()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();

        $url = 'http://' . $site->getHost() . "/search?q=anal";
        $crawler = $client->request('GET', $url);
        $h1_count = $crawler->filter('h1')->count();

        $this->assertTrue($h1_count == 1 ? true : false);
    }

    public function testCheckForH2()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();

        $url = 'http://' . $site->getHost() . "/search?q=anal";
        $crawler = $client->request('GET', $url);
        $h1_count = $crawler->filter('h2')->count();

        $this->assertTrue($h1_count == 1 ? true : false);
    }

    public function testCheckNonEmptyTitle()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();

        $url = 'http://' . $site->getHost() . "/search?q=anal";
        $crawler = $client->request('GET', $url);
        $title = $crawler->filter('title')->text();

        $this->assertTrue(strlen($title) > 0 ? true : false);
    }

    public function testCheckNonEmptyDescription()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();

        $url = 'http://' . $site->getHost() . "/search?q=anal";
        $crawler = $client->request('GET', $url);
        $description = $crawler->filterXpath('//meta[@name="description"]')->attr('content');

        $this->assertTrue(strlen($description) > 0 ? true : false);
    }

    public function testCheckMetaNoIndex()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();

        $url = 'http://' . $site->getHost() . "/search?q=anal";
        $crawler = $client->request('GET', $url);
        $noindex = $crawler->filterXpath('//meta[@name="robots"]')->attr('content');

        $this->assertTrue($noindex == 'noindex' ? true : false);
    }
}