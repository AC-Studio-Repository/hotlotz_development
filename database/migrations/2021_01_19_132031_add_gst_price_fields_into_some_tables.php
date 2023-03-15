<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGstPriceFieldsIntoSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function(Blueprint $table) {
            $table->string('sold_price_inclusive_gst')->nullable()->after('sold_price');
            $table->string('sold_price_exclusive_gst')->nullable()->after('sold_price_inclusive_gst');
        });

        Schema::table('item_lifecycles', function(Blueprint $table) {
            $table->string('sold_price_inclusive_gst')->nullable()->after('sold_price');
            $table->string('sold_price_exclusive_gst')->nullable()->after('sold_price_inclusive_gst');
        });

        Schema::table('auction_items', function(Blueprint $table) {
            $table->string('sold_price_inclusive_gst')->nullable()->after('sold_price');
            $table->string('sold_price_exclusive_gst')->nullable()->after('sold_price_inclusive_gst');
        });

        Schema::table('item_histories', function(Blueprint $table) {
            $table->string('sold_price_inclusive_gst')->nullable()->after('sold_price');
            $table->string('sold_price_exclusive_gst')->nullable()->after('sold_price_inclusive_gst');
        });

        Schema::table('xero_invoices', function(Blueprint $table) {
            $table->string('sold_price_inclusive_gst')->nullable()->after('price');
            $table->string('sold_price_exclusive_gst')->nullable()->after('sold_price_inclusive_gst');
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
