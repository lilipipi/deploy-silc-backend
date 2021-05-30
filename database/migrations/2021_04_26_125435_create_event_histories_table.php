<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_histories', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->timestamp('date')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('asset_id')->nullable();

            $table->index('asset_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_histories');
    }
}