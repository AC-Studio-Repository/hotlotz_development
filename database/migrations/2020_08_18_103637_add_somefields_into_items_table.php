<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomefieldsIntoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('brand')->nullable();
            $table->boolean('is_tree_planted')->default(0)->nullable();
        });

        Schema::table('auction_items', function (Blueprint $table) {
            $table->longText('sr_lot_data')->nullable()->after('sold_date');
            $table->longText('sr_sale_result')->nullable()->after('sr_lot_data');
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
