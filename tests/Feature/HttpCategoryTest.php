<?php

namespace Tests\Feature;

use Tests\TestCase;

class HttpCategoryTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCheckStatusCode()
    {
        $response = $this->get("/");
        $response->assertStatus(200);
    }
}