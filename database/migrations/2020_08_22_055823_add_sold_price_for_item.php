<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoldPriceForItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('sold_price',15,2)->nullable()->after('sold_date');
            $table->integer('buyer_id')->nullable()->after('sold_price');

            $table->integer('cataloguing_approver_id')->nullable();
            $table->boolean('is_cataloguing_approved')->default(0)->nullable();
            $table->dateTime('cataloguing_approval_date')->nullable();

            $table->integer('valuation_approver_id')->nullable();
            $table->boolean('is_valuation_approved')->default(0)->nullable();
            $table->dateTime('valuation_approval_date')->nullable();

            $table->boolean('is_fee_structure_needed')->default(1)->nullable();
        });

        Schema::table('item_lifecycles', function (Blueprint $table) {
            $table->decimal('sold_price',15,2)->nullable()->after('sold_date');
            $table->integer('buyer_id')->nullable()->after('sold_price');
        });

        Schema::table('auction_items', function (Blueprint $table) {
            $table->decimal('sold_price',15,2)->nullable()->after('sold_date');
            $table->integer('buyer_id')->nullable()->after('sold_price');
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
