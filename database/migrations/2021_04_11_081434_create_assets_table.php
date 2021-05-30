<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('assetType')->nullable();
            $table->string('assetTitle')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('investmentGoal')->nullable();
            $table->string('investmentTerm')->nullable();
            $table->unsignedBigInteger('minInvestmentAmount')->nullable();
            $table->unsignedBigInteger('interestRate')->nullable();
            $table->unsignedBigInteger('investmentReceived')->nullable();
            $table->string('assetInfo')->nullable();
            $table->boolean('isVerified')->default(0);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
}