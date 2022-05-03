<?php

namespace Tests\Feature;

use App\Models\DaysEarningsIncentive;
use App\Models\OrderDemaecan;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\TestCase;

class OrderTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseMigrations;
    /**
     * @test
     */
    public function api_orderにPOSTでアクセスできる()
    {
        $user = $this->signIn();

        $reqest_body = [
            'earnings_incentive' => 2.0
        ];

        $response = $this->json('POST', 'api/order', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function api_orderにPOSTでデータが追加できる()
    {
        $user = $this->signIn();

        $reqest_body = [
            'earnings_incentive' => 2.0
        ];

        $response = $this->json('POST', 'api/order', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response->assertStatus(201);
    }

    /**
     * @test
     */
    public function api_orderでPOSTでstatusテーブルも更新できる()
    {
        $user = $this->signIn();

        OrderDemaecan::factory()->create([
            'user_id' => $user->id,
            'earnings_base' => 715,
            'earnings_total' => 1430,
            'earnings_incentive' => 2.0,
            'prefecture_id' => $user->prefecture_id,
        ]);

        Status::factory()->create([
            'user_id' => $user->id,
            'days_earnings_total' => 1430,
            'actual_cost' => 0,
            'days_earnings_qty' => 1,
            'prefecture_id' => $user->prefecture_id,
        ]);

        $reqest_body = [
            'earnings_incentive' => 2.0
        ];

        $response = $this->json('POST', 'api/order', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $this->assertDatabaseHas('statuses', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * @test
     */
    public function api_orderでGETでアクセスできる()
    {
        $user = $this->signIn();

        $response = $this->json('GET', 'api/order?date=20220331', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_orderでGETでクエリなしで400エラー()
    {
        $user = $this->signIn();

        $response = $this->json('GET', 'api/order', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response->assertStatus(400);
    }

    /**
     * @test
     */
    public function api_orderでGETでクエリありのアクセスでレスポンス()
    {
        $user = $this->signIn();

        $response = $this->json('GET', 'api/order?date=20220226', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure(
                [
                    "data" => [
                        '*' =>[
                            'id',
                            'user_id',
                            'prefecture_id',
                            'earnings_incentive',
                            'earnings_base',
                            'earnings_total',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]
            );
    }

    /**
     * @test
     */
    public function patch_api_order_idで204のレスポンス()
    {
        $user = $this->signIn();

        $reqest_body_post = [
            'earnings_incentive' => 2.0
        ];

        $reqest_body_patch = [
            'earnings_base' => 660,
            'earnings_incentive' => 1.1,
            'update_date_time' => '2022-04-04 14:40:26'
        ];

        $response = $this->json('POST', 'api/order', $reqest_body_post, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response = $this->json('patch', 'api/order/1', $reqest_body_patch, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response->assertStatus(204);
    }

    /**
     * @test
     */
    public function patch_api_order_idでデータが存在しないときに404のレスポンス()
    {
        $user = $this->signIn();

        $reqest_body_post = [
            'earnings_incentive' => 2.0
        ];

        $reqest_body_patch = [
            'earnings_base' => 660,
            'earnings_incentive' => 1.1,
            'update_date_time' => '2022-04-04 14:40:26'
        ];

        $response = $this->json('POST', 'api/order', $reqest_body_post, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response = $this->json('patch', 'api/order/2', $reqest_body_patch, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function destroy_api_order_idで204のレスポンス()
    {
        $user = $this->signIn();

        $reqest_body_post = [
            'earnings_incentive' => 2.0
        ];

        $response = $this->json('POST', 'api/order', $reqest_body_post, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response = $this->json('delete', 'api/order/1', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response->assertStatus(204);
    }

    /**
     * @test
     */
    public function destroy_api_order_idでデータが存在しないときに404のレスポンス()
    {
        $user = $this->signIn();

        $reqest_body_post = [
            'earnings_incentive' => 2.0
        ];

        $response = $this->json('POST', 'api/order', $reqest_body_post, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response = $this->json('delete', 'api/order/2', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function post_api_orderでtodayIncentiveを登録済みのときのレコードチェック()
    {
        $user = $this->signIn();

        $test_insert_data = [];
        $check_data['data'] = [];
        for ($hour = 7,$today_incentive = 1.0; $hour <= 24; $hour++,$today_incentive += 0.1) {
            $current_time = Carbon::now();
            $test_insert_data[] = [
                'user_id' => $user['id'],
                'earnings_incentive' => $today_incentive,
                'incentive_hour' => Carbon::createFromTime($hour,0,0,'Asia/Tokyo'),
                'created_at' => $current_time,
                'updated_at' => $current_time,
            ];
            $today_incentives[] = [
                'user_id' => $user['id'],
                'earnings_incentive' => $today_incentive,
                'created_at' => $current_time
            ]; 
        }

        DaysEarningsIncentive::insert($test_insert_data);

        $reqest_body = [
            'earnings_incentive' => 1.5
        ];

        $response = $this->json('POST', 'api/order', $reqest_body, [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $user['access_token']
        ]);

        //7時=>1.0,8時=>1.1,9時=>1.2,10時=>1.3,11時=>1.4,
        //12時=>1.5,13時=>1.6,14時=>1.7,15時=>1.8,16時=>1.9,
        //17時=>2.0,18時=>2.1,19時=>2.2,20時=>2.3,21時=>2.4
        //22時=>2.5,23時=>2.6,24時=>2.7
        $this->assertDatabaseHas('order_demaecans', [
            'user_id' => $user['id'],
            'earnings_incentive' => 2.5,
        ]);

        $response->assertStatus(201);
    }
}
