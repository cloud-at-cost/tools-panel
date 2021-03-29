<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_balances', function (Blueprint $table) {
            $table->id();

            $table->string('wallet', 50);
            $table->string('unit', 3);

            $table->unsignedInteger('transactions');
            $table->unsignedInteger('total_received');
            $table->unsignedInteger('total_sent');
            $table->unsignedInteger('total_fees');
            $table->unsignedInteger('final_balance');

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
        Schema::dropIfExists('wallet_balances');
    }
}
