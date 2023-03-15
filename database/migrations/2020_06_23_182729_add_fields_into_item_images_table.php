<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsIntoItemImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->uuid('lot_id')->nullable()->after('county_state');
            $table->string('sr_status')->nullable()->after('lot_id');
        });

        Schema::table('item_images', function (Blueprint $table) {
            $table->uuid('lot_image_id')->nullable()->after('file_path');
            $table->string('sr_status')->nullable()->after('lot_image_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
