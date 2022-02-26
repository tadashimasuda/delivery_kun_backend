<?php

namespace Tests\Feature;

use App\Models\Prefecture;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Laravel\Passport\Passport;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    // use RefreshDatabase;

    /**
     *  @test
     */
    public function api_registerにPOSTでアクセスできる()
    {
        $request_body = [
            'email' => 'sample@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        $response = $this->json('POST', 'api/register', $request_body, ['Accept' => 'application/json']);
        $response->assertStatus(201);
    }

    /**
     *  @test
     */
    public function api_registerにPOSTでアクセスするとJSONが返却()
    {
        $request_body = [
            'email' => 'sample@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        $response = $this->json('POST', 'api/register', $request_body, ['Accept' => 'application/json']);
        $this->assertThat($response->content(), $this->isJson());
    }

    /**
     *  @test
     */
    public function api_registerにPOSTでアクセスするとユーザが新規作成される()
    {
        $request_body = [
            'email' => 'sample@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $this->json('POST', 'api/register', $request_body, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "data" => [
                    'id',
                    'name',
                    'email',
                    'vehicle_model',
                    'access_token',
                ]
            ]);
    }

    /**
     *  @test
     */
    public function api_loginにPOSTでアクセスできる()
    {
        User::factory()->create([
            'name' => 'sampleUser',
            'email' => 'sample@gmail.com',
            'password' =>  bcrypt('password'),
            'prefecture_id' => 1,
            'vehicle_model' => 0
        ]);

        $request_body = [
            'email' => 'sample@gmail.com',
            'password' => 'password',
        ];
        $this->json('POST', 'api/login', $request_body, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_loginにPOSTでアクセスするとJSONが返却()
    {

        $request_body = [
            'email' => 'sample@gmail.com',
            'password' => 'password',
        ];
        $response = $this->json('POST', 'api/login', $request_body, ['Accept' => 'application/json']);
        $this->assertThat($response->content(), $this->isJson());
    }

    /**
     * @test
     */
    public function api_loginにアクセスするとログインできる()
    {
        User::factory()->create([
            'name' => 'sampleUser',
            'email' => 'sample@gmail.com',
            'password' =>  bcrypt('password'),
            'prefecture_id' => 1,
            'vehicle_model' => 0
        ]);

        $request_body = [
            'email' => 'sample@gmail.com',
            'password' => 'password',
        ];

        $this->json('POST', 'api/login', $request_body, ['Accept' => 'application/json'])
            ->assertStatus(200)
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
     * @test
     */
    public function api_logoutにアクセスできる()
    {
        $token = $this->signIn();

        $this->json('POST', 'api/logout', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function api_logoutでログアウトできる()
    {
        $token = $this->signIn();

        $response = $this->json('POST', 'api/logout', [], [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertOk();
    }
}
