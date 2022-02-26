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
}
