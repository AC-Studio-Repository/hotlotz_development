<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lot_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('item_image_id');
            $table->uuid('item_id');
            $table->uuid('auction_id');
            $table->uuid('sr_auction_id');
            $table->uuid('lot_id');
            $table->uuid('lot_image_id');
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            
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
        Schema::dropIfExists('lot_images');
    }
}
