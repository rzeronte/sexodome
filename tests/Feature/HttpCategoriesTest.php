<?php

namespace Tests\Feature;

use Tests\TestCase;

class HttpCategoriesTest extends TestCase
{
    public function testCheckStatusCode()
    {
        $response = $this->get("/");
        $response->assertStatus(200);
    }

    public function testForH1Tag()
    {
        $response = $this->get("/");
        $response->assertStatus(200);
    }

}