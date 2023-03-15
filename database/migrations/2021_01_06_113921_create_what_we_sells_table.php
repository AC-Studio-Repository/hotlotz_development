<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatWeSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('what_we_sells', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('category_id')->nullable();
            $table->string('caption')->nullable();
            $table->string('price_status')->default('N');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('full_path')->nullable();
            $table->string('detail_banner_file_name')->nullable();
            $table->string('detail_banner_file_path')->nullable();
            $table->string('detail_banner_full_path')->nullable();
            $table->integer('key_contact_1')->nullable();
            $table->integer('key_contact_2')->nullable();
            $table->integer('order')->nullable();
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
        Schema::dropIfExists('what_we_sells');
    }
}
