<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use DatabaseMigrations;

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

    /**
     * @test
     */
    public function api_statusにGETでアクセスでJSONのレスポンス()
    {
        $user = $this->signIn();

        $response = $this->json('GET', 'api/status?date=20220227&user_id=1', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response->assertStatus(200);
    }
}
