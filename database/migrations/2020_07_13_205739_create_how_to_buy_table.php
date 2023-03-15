<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHowToBuyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('how_to_buy', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('banner_image')->nullable();
            $table->string('caption')->nullable();
            $table->string('blog_header_1')->nullable();
            $table->text('blog_1')->nullable();
            $table->string('blog_header_2')->nullable();
            $table->text('blog_2')->nullable();
            $table->string('blog_header_3')->nullable();
            $table->text('blog_3')->nullable();
            $table->text('content')->nullable();
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
        Schema::dropIfExists('how_to_buy');
    }
}
