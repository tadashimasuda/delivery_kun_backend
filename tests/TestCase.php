<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    public function setup(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDataSeeder']);
        Artisan::call('passport:install');
    }

    public function signIn($user = null)
    {
        $user = User::factory()->create([
            'name' => 'sampleUser',
            'email' => 'sampleTaro@test.com',
            'password' =>  bcrypt('password'),
            'prefecture_id' => 1,
            'vehicle_model' => 0
        ]);
        $token = $user->createToken('access_token')->accessToken;
        return $token;
    }
}
