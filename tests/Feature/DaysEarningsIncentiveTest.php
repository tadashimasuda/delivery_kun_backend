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
        $reqest_body['data']=[];
        for ($hour=7; $hour <= 24; $hour++) { 
            $reqest_body['data'][] = [
                "hour" => $hour,
                "incentive" => 1.0
            ];
        }

        $response = $this->json('POST', 'api/incentive', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_incentiveにPOSTでrequestBodyなしで403エラー()
    {
        $user = $this->signIn();

        $reqest_body = [];

        $response = $this->json('POST', 'api/incentive', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response->assertStatus(422);

    }
}
