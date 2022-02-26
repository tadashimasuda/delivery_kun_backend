<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Tests\Feature\UserTest;

class OrderTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseMigrations;

    /**
     * @test
     */
    public function api_orderにPOSTでアクセスできる()
    {
        $token = $this->signIn();

        $response = $this->json('POST', 'api/order', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
    }
}
