<?php

namespace Tests\Feature;

use App\Models\OrderDemaecan;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
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
}
