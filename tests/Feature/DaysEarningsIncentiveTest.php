<?php

namespace Tests\Feature;

use App\Models\DaysEarningsIncentive;
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
                'incentive_hour' => $hour,
                'earnings_incentive' => 1.2,
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
                'incentive_hour' => $hour,
                'earnings_incentive' => 1.2,
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

    /**
     * @test
     */
    public function api_incentiveにPOSTでデータが存在するときにupdateできる()
    {
        $user = $this->signIn();

        $reqest_body['data']=[];
        $test_insert_data = [];
        $check_data = [];

        for ($hour=7; $hour <= 24; $hour++) { 
            $reqest_body['data'][] = [
                'incentive_hour' => $hour,
                'earnings_incentive' => 1.5,
            ];
            $test_insert_data[] = [
                'user_id' => $user['id'],
                'earnings_incentive' => 1.2,
                'incentive_hour' => Carbon::createFromTime($hour,0,0,'Asia/Tokyo'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $check_data[] = [
                'user_id' => $user['id'],
                'earnings_incentive' => 1.5,
                'incentive_hour' => Carbon::createFromTime($hour,0,0,'Asia/Tokyo')
            ];
        }

        DaysEarningsIncentive::insert($test_insert_data);

        $response = $this->json('POST', 'api/incentive', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
 
        foreach ($check_data as $row) {
            $this->assertDatabaseHas('days_earnings_incentives', $row);
        }

        $response->assertStatus(204);
    }

    /**
     * @test
     */
    public function api_incentiveにGETでアクセスできる()
    {
        $user = $this->signIn();

        $response = $this->json('GET', 'api/incentive', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response->assertStatus(204);
    }

    /**
     * @test
     */
    public function api_incentiveにGETでアクセスでNODATAで特定のメッセージが返却()
    {
        $user = $this->signIn();

        $response = $this->json('GET', 'api/incentive', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response->assertNoContent(204);
    }

    /**
     * @test
     */
    public function api_incentiveにGETでアクセスでデータがある時のデータのレスポンス()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();

        $test_insert_data = [];
        $check_data['data'] = [];
        for ($hour=7; $hour <= 24; $hour++) { 
            $test_insert_data[] = [
                'user_id' => $user['id'],
                'earnings_incentive' => 1.2,
                'incentive_hour' => Carbon::createFromTime($hour,0,0,'Asia/Tokyo'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $check_data['data'][] = [
                'incentive_hour' => Carbon::createFromTime($hour,0,0,'Asia/Tokyo')->format('H'),
                'earnings_incentive' => 1.2,
            ];
        }

        DaysEarningsIncentive::insert($test_insert_data);

        $response = $this->json('GET', 'api/incentive', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response
        ->assertStatus(200)
        ->assertJson($check_data);
    }
}
