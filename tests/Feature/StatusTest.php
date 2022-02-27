<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StatusTest extends TestCase
{
    /**
     * @test
     */
    public function api_statusにGETでアクセスできる()
    {
        $user = $this->signIn();

        $response = $this->json('GET', 'api/status', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response->assertStatus(200);
    }
}
