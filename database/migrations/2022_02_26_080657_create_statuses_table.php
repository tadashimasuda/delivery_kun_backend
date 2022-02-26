<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->float('days_earnings_total')->default(0);
            $table->integer('actual_cost')->default(0);
            $table->integer('days_earnings_qty')->default(0);
            $table->unsignedBigInteger('prefecture_id');
            $table->timestamps();
            $table->timestamp('finish_at')->default(DB::raw('CURRENT_TIMESTAMP'));;

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('prefecture_id')->references('id')->on('prefectures');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
