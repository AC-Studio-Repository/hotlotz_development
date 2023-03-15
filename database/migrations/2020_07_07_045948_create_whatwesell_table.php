<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatwesellTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('what_we_sell', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            // $table->string('price')->nullable();
            $table->boolean('price_status')->default(0);
            // $table->string('buyerlevel')->nullable();
            $table->string('caption')->nullable();
            $table->string('list_image_file_path')->nullable();
            $table->string('list_banner_image_file_path')->nullable();
            $table->text('description')->nullable();
            // $table->string('detail_image_1_price')->nullable();
            $table->boolean('detail_image_1_price_status')->default(0);
            // $table->string('detail_image_1_buyerlevel')->nullable();
            $table->string('detail_image_1_title')->nullable();
            $table->string('detail_image_1_caption')->nullable();
            $table->string('detail_image_1_file_path')->nullable();
            // $table->string('detail_image_2_price')->nullable();
            $table->boolean('detail_image_2_price_status')->default(0);
            // $table->string('detail_image_2_buyerlevel')->nullable();
            $table->string('detail_image_2_title')->nullable();
            $table->string('detail_image_2_caption')->nullable();
            $table->string('detail_image_2_file_path')->nullable();
            // $table->string('detail_image_3_price')->nullable();
            $table->boolean('detail_image_3_price_status')->default(0);
            // $table->string('detail_image_3_buyerlevel')->nullable();
            $table->string('detail_image_3_title')->nullable();
            $table->string('detail_image_3_caption')->nullable();
            $table->string('detail_image_3_file_path')->nullable();
            // $table->string('detail_image_4_price')->nullable();
            $table->boolean('detail_image_4_price_status')->default(0);
            // $table->string('detail_image_4_buyerlevel')->nullable();
            $table->string('detail_image_4_title')->nullable();
            $table->string('detail_image_4_caption')->nullable();
            $table->string('detail_image_4_file_path')->nullable();
            // $table->string('detail_image_5_price')->nullable();
            $table->boolean('detail_image_5_price_status')->default(0);
            // $table->string('detail_image_5_buyerlevel')->nullable();
            $table->string('detail_image_5_title')->nullable();
            $table->string('detail_image_5_caption')->nullable();
            $table->string('detail_image_5_file_path')->nullable();
            // $table->string('detail_image_6_price')->nullable();
            $table->boolean('detail_image_6_price_status')->default(0);
            // $table->string('detail_image_6_buyerlevel')->nullable();
            $table->string('detail_image_6_title')->nullable();
            $table->string('detail_image_6_caption')->nullable();
            $table->string('detail_image_6_file_path')->nullable();
            // $table->string('detail_image_7_price')->nullable();
            $table->boolean('detail_image_7_price_status')->default(0);
            // $table->string('detail_image_7_buyerlevel')->nullable();
            $table->string('detail_image_7_title')->nullable();
            $table->string('detail_image_7_caption')->nullable();
            $table->string('detail_image_7_file_path')->nullable();
            // $table->string('detail_image_8_price')->nullable();
            $table->boolean('detail_image_8_price_status')->default(0);
            // $table->string('detail_image_8_buyerlevel')->nullable();
            $table->string('detail_image_8_title')->nullable();
            $table->string('detail_image_8_caption')->nullable();
            $table->string('detail_image_8_file_path')->nullable();
            // $table->string('detail_image_9_price')->nullable();
            $table->boolean('detail_image_9_price_status')->default(0);
            // $table->string('detail_image_9_buyerlevel')->nullable();
            $table->string('detail_image_9_title')->nullable();
            $table->string('detail_image_9_caption')->nullable();
            $table->string('detail_image_9_file_path')->nullable();
            // $table->string('detail_image_10_price')->nullable();
            $table->boolean('detail_image_10_price_status')->default(0);
            // $table->string('detail_image_10_buyerlevel')->nullable();
            $table->string('detail_image_10_title')->nullable();
            $table->string('detail_image_10_caption')->nullable();
            $table->string('detail_image_10_file_path')->nullable();
            // $table->string('detail_image_11_price')->nullable();
            $table->boolean('detail_image_11_price_status')->default(0);
            // $table->string('detail_image_11_buyerlevel')->nullable();
            $table->string('detail_image_11_title')->nullable();
            $table->string('detail_image_11_caption')->nullable();
            $table->string('detail_image_11_file_path')->nullable();
            // $table->string('detail_image_12_price')->nullable();
            $table->boolean('detail_image_12_price_status')->default(0);
            // $table->string('detail_image_12_buyerlevel')->nullable();
            $table->string('detail_image_12_title')->nullable();
            $table->string('detail_image_12_caption')->nullable();
            $table->string('detail_image_12_file_path')->nullable();
            $table->integer('created_by')->default(1);
            $table->integer('updated_by')->default(1);
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('what_we_sell');
    }
}
