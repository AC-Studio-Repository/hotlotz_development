<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageMarketplaceBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homepage_marketplace_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('banner')->nullable();
            $table->text('caption')->nullable();
            $table->text('file_path')->nullable();
            $table->string('type')->nullable();
            $table->tinyInteger('inactive')->default('0');
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
        Schema::dropIfExists('homepage_marketplace_banners');
    }
}
