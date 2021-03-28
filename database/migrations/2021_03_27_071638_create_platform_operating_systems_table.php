<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformOperatingSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_operating_systems', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('platform_id');

            $table->unsignedInteger('identifier');
            $table->string('name', 50);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('platform_id')
                ->references('id')
                ->on('platforms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platform_operating_systems');
    }
}
