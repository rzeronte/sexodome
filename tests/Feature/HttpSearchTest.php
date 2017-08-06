<?php

namespace Tests\Feature;

use Tests\TestCase;

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
}