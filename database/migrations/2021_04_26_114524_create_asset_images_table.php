<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_images', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->string("imageAddress");
            $table->timestamps();

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
        Schema::dropIfExists('asset_images');
    }
}