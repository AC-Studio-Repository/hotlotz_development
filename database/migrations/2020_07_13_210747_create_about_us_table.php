<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_us', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('banner_image')->nullable();
            $table->string('caption')->nullable();
            $table->string('blog_header_1')->nullable();
            $table->text('blog_1')->nullable();
            $table->string('blog_header_2')->nullable();
            $table->text('blog_2')->nullable();
            $table->string('contact_country_1')->nullable();
            $table->string('email_1')->nullable();
            $table->string('contact_country_2')->nullable();
            $table->string('email_2')->nullable();
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
        Schema::dropIfExists('about_us');
    }
}
