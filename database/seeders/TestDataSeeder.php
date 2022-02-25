<?php

namespace Database\Seeders;

use App\Models\Prefecture;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Prefecture::factory()->count(2)->create()->each(function ($prefecture) {
            User::factory()->count(2)->make()->each(function($user) use ($prefecture){
                $prefecture->users()->save($user);
            });
            }
        );
    }
}
