<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinerPayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miner_payouts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('miner_id');
            $table->unsignedBigInteger('amount');
            $table->string('type', 50);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('miner_id')
                ->references('id')
                ->on('miners');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('miner_payouts');
    }
}
