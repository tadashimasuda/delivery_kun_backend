<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     *  @test
     */
    public function api_registerにPOSTでアクセスできる()
    {
        $response = $this->post('/api/register');
        $response -> assertStatus(200);
    }
}
