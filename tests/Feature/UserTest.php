<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();
        $this->artisan('db:seed',['--class' => 'TestDataSeeder']);
    }

    /**
     *  @test
     */
    public function api_registerにPOSTでアクセスできる()
    {
        $response = $this->post('/api/register');
        $response -> assertStatus(200);
    }

    /**
     *  @test
     */
    public function api_registerにPOSTでアクセスするとJSONが返却()
    {
        $response = $this->post('/api/register');
        $this->assertThat($response->content(),$this->isJson());
    }

    /**
     *  @test
     */
    public function api_registerにPOSTでアクセスすると要件通りに返却()
    {
        $response = $this->post('/api/register');
        $users = $response->json();
        $user = $users[0];
        $this->assertSame(['id','name','email','prefecture_id','vehicle_model'],array_keys($user));
    }
}
