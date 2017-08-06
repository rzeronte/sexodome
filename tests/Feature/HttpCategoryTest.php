<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Model\Site;
use Goutte\Client;

class HttpCategoryTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCheckStatusCode()
    {
        $site = Site::find(env('DEMO_SITE_ID'))->first();
        $category = $site->categories()->where('status', 1)->first();
        $uri = '/' . $site->category_url . '/' . $category->translation($site->language->id)->permalink;

        $response = $this->get($uri);
        $response->assertStatus(200);
    }

    public function testCheckForH1()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();

        $category = $site->categories()->where('status', 1)->first();
        $uri = '/' . $site->category_url . '/' . $category->translation($site->language->id)->permalink;
        $url = 'http://' . $site->getHost() . $uri;
        $crawler = $client->request('GET', $url);
        $h1_count = $crawler->filter('h1')->count();


        $this->assertTrue($h1_count == 1 ? true : false);
    }

    public function testCheckForH2()
    {
        $client = new Client();
        $site = Site::find(env('DEMO_SITE_ID'))->first();

        $category = $site->categories()->where('status', 1)->first();
        $uri = '/' . $site->category_url . '/' . $category->translation($site->language->id)->permalink;
        $url = 'http://' . $site->getHost() . $uri;
        $crawler = $client->request('GET', $url);
        $h1_count = $crawler->filter('h2')->count();


        $this->assertTrue($h1_count == 1 ? true : false);
    }
}