<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullableColumnToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('address1')->nullable()->change();
            $table->string('town_city')->nullable()->change();
            $table->string('postcode')->nullable()->change();
            $table->string('country_code')->nullable()->change();
            $table->string('county_state')->nullable()->change();
            $table->string('address1')->nullable()->change();
            $table->string('address1')->nullable()->change();
            $table->string('address1')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
}
