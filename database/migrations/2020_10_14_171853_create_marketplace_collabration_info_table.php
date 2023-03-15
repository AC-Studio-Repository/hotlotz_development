<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketplaceCollabrationInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_collabration_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image_properties')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('caption')->nullable();
            $table->string('title_header')->nullable();
            $table->text('title_blog')->nullable();
            $table->integer('created_by')->default(1);
            $table->integer('updated_by')->default(1);
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
        Schema::dropIfExists('marketplace_collabration_info');
    }
}
