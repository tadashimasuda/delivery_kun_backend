<?php

namespace Tests\Feature;

use Carbon\Carbon;
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
                'hour' => $hour,
                'incentive' => 1.2,
            ];
        }

        $response = $this->json('POST', 'api/incentive', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response->assertStatus(204);
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

    /**
     * @test
     */
    public function api_incentiveにPOSTでデータが存在しないときにinsertできる()
    {
        $user = $this->signIn();

        $reqest_body['data']=[];
        for ($hour=7; $hour <= 24; $hour++) { 
            $reqest_body['data'][] = [
                'hour' => $hour,
                'incentive' => 1.2,
            ];
        }

        $test_time = Carbon::createFromTime(9,0,0);
        $response = $this->json('POST', 'api/incentive', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $this->assertDatabaseHas('days_earnings_incentives', [
            'user_id' => $user['id'],
            'earnings_incentive' => 1.2,
            'incentive_hour' => $test_time
        ]);
        $response->assertStatus(204);
    }
}
