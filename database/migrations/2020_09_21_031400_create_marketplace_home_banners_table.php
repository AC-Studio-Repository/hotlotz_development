<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketplaceHomeBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_home_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('header_title')->nullable();
            $table->string('banner')->nullable();
            $table->text('caption')->nullable();
            $table->text('file_path')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('marketplace_home_banners');
    }
}
