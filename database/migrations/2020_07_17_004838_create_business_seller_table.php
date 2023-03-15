<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessSellerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_seller', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('banner_image')->nullable();
            $table->string('caption')->nullable();
            $table->string('blog_header_1')->nullable();
            $table->text('blog_1')->nullable();
            $table->string('blog_header_2')->nullable();
            $table->text('blog_2')->nullable();
            $table->string('blog_header_3')->nullable();
            $table->text('blog_3')->nullable();
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
        Schema::dropIfExists('business_seller');
    }
}
