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

        $reqest_body = [
            'earnings_incentive' => 2.0
        ];

        $response = $this->json('POST', 'api/order', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(201);
    }

    /**
     * @test
     */

    public function api_orderにPOSTでデータが追加できる()
    {
        $token = $this->signIn();

        $reqest_body = [
            'earnings_incentive' => 2.0
        ];

        $response = $this->json('POST', 'api/order', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(201);
    }
}
