<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationCmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_cms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('banner')->nullable();
            $table->string('caption')->nullable();
            $table->string('title_header')->nullable();
            $table->text('title_blog')->nullable();
            $table->string('direction_header')->nullable();
            $table->text('direction_blog')->nullable();
            $table->text('saleroom_details')->nullable();
            $table->string('mon')->nullable();
            $table->string('tue')->nullable();
            $table->string('wed')->nullable();
            $table->string('thur')->nullable();
            $table->string('fri')->nullable();
            $table->string('sat')->nullable();
            $table->string('sun')->nullable();
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
        Schema::dropIfExists('location_cms');
    }
}
