<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXeroItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xero_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item_code');
            $table->string('item_name');
            $table->text('purchases_description')->nullable();
            $table->integer('purchases_account')->default(0);
            $table->text('sales_description')->nullable();
            $table->integer('sales_account')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xero_items');
    }
}
