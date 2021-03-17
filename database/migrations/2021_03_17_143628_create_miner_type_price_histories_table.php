<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinerTypePriceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miner_type_price_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('miner_type_id');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('bitcoins_per_month');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('miner_type_id')
                ->references('id')
                ->on('miner_types');
        });

        Schema::table('miner_types', function(Blueprint $table) {
            $table->dropColumn('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('miner_types', function(Blueprint $table) {
            $table->unsignedInteger('price');
        });

        Schema::dropIfExists('miner_type_price_histories');
    }
}
