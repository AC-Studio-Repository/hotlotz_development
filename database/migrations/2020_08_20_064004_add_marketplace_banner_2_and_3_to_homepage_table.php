<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarketplaceBanner2And3ToHomepageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homepage', function (Blueprint $table) {
            $table->string('marketpalce_image_2_path')->nullable();
            $table->string('marketplace_image_2')->nullable();
            $table->string('marketpalce_image_3_path')->nullable();
            $table->string('marketplace_image_3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homepage', function (Blueprint $table) {
            //
        });
    }
}
