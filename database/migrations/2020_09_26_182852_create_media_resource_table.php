<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_resource', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('banner_image')->nullable();
            $table->string('caption')->nullable();
            $table->string('blog_header_1')->nullable();
            $table->text('blog_1')->nullable();
            $table->string('contact_country_1')->nullable();
            $table->string('contact_email_1')->nullable();
            $table->string('contact_country_2')->nullable();
            $table->string('contact_email_2')->nullable();
            $table->string('our_asset_file_path')->nullable();
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
        Schema::dropIfExists('media_resource');
    }
}
