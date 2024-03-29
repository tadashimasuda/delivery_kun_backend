<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEarningsDistanveBaseTypeToOrderDemaecansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_demaecans', function (Blueprint $table) {
            $table->integer('earnings_distance_base_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_demaecans', function (Blueprint $table) {
            $table->integer('earnings_distance_base_type')->nullable();
        });
    }
}
