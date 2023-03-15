<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketplaceCollaborationBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_collaboration_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('banner')->nullable();
            $table->string('header_title')->nullable();
            $table->text('title_blog')->nullable();
            $table->text('file_path')->nullable();
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
        Schema::dropIfExists('marketplace__collaboration_banners');
    }
}
