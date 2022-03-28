<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker;

class PrefectureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'prefecture' => Faker\Factory::create('ja_JP')->unique()->prefecture(),
            'earnings_base' => Faker\Factory::create()->randomElement(['715', '615', '515'])
        ];
    }
}
