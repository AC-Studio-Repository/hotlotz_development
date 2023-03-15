<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXeroTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xero_trackings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['Business', 'Category'])->default('Business');
            $table->string('name');
            $table->uuid('xero_tracking_category_id')->nullable();
            $table->uuid('xero_tracking_option_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xero_trackings');
    }
}
