<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAverageThirtyDayPayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('average_thirty_day_payouts', function (Blueprint $table) {
            $table->id();

            $table->string('classification', 5);
            $table->unsignedBigInteger('bitcoins_per_month');
            $table->unsignedBigInteger('total_sample_size');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('average_thirty_day_payouts');
    }
}
