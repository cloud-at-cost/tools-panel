<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeIdentifierNullableOnMinersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('miners', function(Blueprint $table) {
            $table->string('identifier')
                ->nullable()
                ->change();
        });
    }
}
