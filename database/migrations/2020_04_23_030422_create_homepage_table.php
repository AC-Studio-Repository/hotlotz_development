<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homepage', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slider_image_1_path')->nullable();
            $table->string('slider_image_1')->nullable();
            $table->string('slider_1_caption_title')->nullable();
            $table->string('slider_1_caption_content')->nullable();
            $table->string('slider_image_2_path')->nullable();
            $table->string('slider_image_2')->nullable();
            $table->string('slider_2_caption_title')->nullable();
            $table->string('slider_2_caption_content')->nullable();
            $table->string('slider_image_3_path')->nullable();
            $table->string('slider_image_3')->nullable();
            $table->string('slider_3_caption_title')->nullable();
            $table->string('slider_3_caption_content')->nullable();
            $table->string('marketpalce_image_path')->nullable();
            $table->string('marketplace_image')->nullable();
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
        Schema::dropIfExists('homepage');
    }
}
