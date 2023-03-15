<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSomeFieldsToUniqueAtCustomersItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('ref_no')->unique()->change();
            $table->string('email')->unique()->change();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->string('item_number')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unique_at_customers_items', function (Blueprint $table) {
            //
        });
    }
}
