<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EarningsIncentiveSheetTest extends TestCase
{

    /**
     * POST api_incentive_sheets
     */
    
    /**
     * @test
     */
    public function api_incentive_sheetsにPOSTデータを追加できる()
    {
        $user = $this->signIn();

        $request_body = [
            'title' => 'test title',
            'earnings_incentives' => []
        ];
        for ($hour=7; $hour <= 23; $hour++) { 
            $request_body['earnings_incentives'][] = [
                sprintf('%02d',$hour) => 2.0,
            ];
        }

        $response = $this->json('POST', 'api/incentive_sheets', $request_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $this->assertDatabaseHas('earnings_incentives_sheets',[
            'title' => 'test title',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function api_incentive_sheetsにPOSTでバリデーションエラーを返す_not_title()
    {
        $user = $this->signIn();

        $request_body = [
            'title' => '',
            'earnings_incentives' => []
        ];
        for ($hour=7; $hour <= 23; $hour++) { 
            $request_body['earnings_incentives'][] = [
                sprintf('%02d',$hour) => 2.0,
            ];
        }

        $response = $this->json('POST', 'api/incentive_sheets', $request_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function api_incentive_sheetsにPOSTでバリデーションエラーを返す_not_earnings_incentives()
    {
        $user = $this->signIn();

        $request_body = [
            'title' => 'sample title',
            'earnings_incentives' => []
        ];

        $response = $this->json('POST', 'api/incentive_sheets', $request_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function api_incentive_sheetsにPOSTでバリデーションエラーを返す_not_array_earnings_incentives()
    {
        $user = $this->signIn();

        $request_body = [
            'title' => 'sample title',
            'earnings_incentives' => 'not array'
        ];

        $response = $this->json('POST', 'api/incentive_sheets', $request_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function api_incentive_sheetsにPOSTでバリデーションエラーを返す_not_match_size_earnings_incentives()
    {
        $user = $this->signIn();

        $request_body = [
            'title' => 'sample title',
            'earnings_incentives' => []
        ];

        for ($hour=7; $hour <= 10; $hour++) { 
            $request_body['earnings_incentives'][] = [
                sprintf('%02d',$hour) => 2.0,
            ];
        }

        $response = $this->json('POST', 'api/incentive_sheets', $request_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response->assertStatus(422);
    }
}
