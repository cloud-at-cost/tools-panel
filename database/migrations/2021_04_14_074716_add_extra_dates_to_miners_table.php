<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraDatesToMinersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('miners', function(Blueprint $table) {
            $table->string('miner_id', 50)
                    ->nullable()
                    ->change();
        });

        Schema::table('miners', function (Blueprint $table) {
            $table->date('purchase_date')
                ->after('amount_paid')
                ->nullable();
            $table->date('estimated_activation_date')
                ->after('purchase_date')
                ->nullable();
            $table->date('activation_date')
                ->after('estimated_activation_date')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('miners', function (Blueprint $table) {
            $table->dropColumn([
                'purchase_date',
                'estimated_activation_date',
                'activation_date',
            ]);
        });

        Schema::table('miners', function(Blueprint $table) {
            $table->string('miner_id', 50)
                ->nullable(false)
                ->change();
        });
    }
}
