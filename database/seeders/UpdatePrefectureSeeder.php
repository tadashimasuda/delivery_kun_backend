<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatePrefectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prefectures')->update(['earnings_base' => 550]);

        DB::table('prefectures')
            ->where('id', 11)
            ->orWhere('id', 12)
            ->orWhere('id', 13)
            ->orWhere('id', 14)
            ->update(['earnings_base' => 600]);
    }
}
