<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Model\Site;

class HttpCategoryTest extends TestCase
{
    public function testCheckStatusCode()
    {
        $site = Site::find(env('DEMO_SITE_ID'))->first();
        $category = $site->categories()->where('status', 1)->first();
        $uri = '/' . $site->category_url . '/' . $category->translation($site->language->id)->permalink;

        $response = $this->get($uri);
        $response->assertStatus(200);
    }
}