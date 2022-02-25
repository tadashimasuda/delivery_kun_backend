<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Artisan;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function setup(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDataSeeder']);
        Artisan::call('passport:install');
    }

    /**
     *  @test
     */
    public function api_registerにPOSTでアクセスできる()
    {
        $request_body = [
            'email' => 'samplea@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        $response = $this->post('/api/register',$request_body);
        $response->assertStatus(201);
    }

    /**
     *  @test
     */
    public function api_registerにPOSTでアクセスするとJSONが返却()
    {
        $request_body = [
            'email' => 'samplea@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        $response = $this->post('/api/register',$request_body);
        $this->assertThat($response->content(), $this->isJson());
    }

    /**
     *  @test
     */
    public function api_registerにPOSTでアクセスするとユーザが新規作成される()
    {
        $request_body = [
            'email' => 'samplea@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->post('api/register', $request_body);
        $response->assertStatus(201)
            ->assertJsonStructure(
                [
                    "data" => [
                        'id',
                        'name',
                        'email',
                        'vehicle_model',
                        'access_token',
                    ]
                ]
            );
    }

    /**
     *  @test
     */
    public function api_loginにPOSTでアクセスできる()
    {
        $response = $this->post('/api/login');
        $response->assertStatus(200);
    }
}
