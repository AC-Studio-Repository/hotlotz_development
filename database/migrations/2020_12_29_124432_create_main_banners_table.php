<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('main_title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('file_name')->nullable();
            $table->text('file_path')->nullable();
            $table->text('full_path')->nullable();
            $table->string('position')->nullable();
            $table->string('color')->nullable();
            $table->string('link')->nullable();
            $table->string('link_name')->nullable();
            $table->integer('order')->nullable();
            $table->tinyInteger('inactive')->default('0');
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
        Schema::dropIfExists('main_banners');
    }
}
