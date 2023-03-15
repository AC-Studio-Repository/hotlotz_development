<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeyContactsToWhatWeSellTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('what_we_sell', function (Blueprint $table) {
            $table->integer('key_contact_1')->unsigned()->default(0)->after('detail_image_12_file_path');
            $table->integer('key_contact_2')->unsigned()->default(0)->after('key_contact_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('what_we_sell', function (Blueprint $table) {
            //
        });
    }
}
