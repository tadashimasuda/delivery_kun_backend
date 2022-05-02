<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DaysEarningsIncentiveTest extends TestCase
{
    /**
     * @test
     */
    public function api_incentiveにPOSTでアクセスできる()
    {
        $user = $this->signIn();

        $reqest_body = [];
        for ($hour=7; $hour <= 24; $hour++) { 
            $reqest_body[$hour] = 1.0;
        }

        $response = $this->json('POST', 'api/incentive', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response->assertStatus(200);
    }
}
