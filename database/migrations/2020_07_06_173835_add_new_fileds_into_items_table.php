<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFiledsIntoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('location')->nullable();
            $table->boolean('is_reserve')->default(1)->nullable()->after('reserve');
            $table->boolean('is_hotlotz_own_stock')->default(0)->nullable();
            $table->string('supplier')->nullable();
            $table->decimal('purchase_cost', 15, 2)->nullable();            
            $table->longText('condition')->nullable();
            $table->longText('provenance')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('weight')->nullable();
            $table->longText('additional_notes')->nullable();

            $table->dateTime('registration_date')->nullable();
            $table->dateTime('seller_agreement_signed_date')->nullable();
            $table->dateTime('entered_marketplace_date')->nullable();
            $table->dateTime('entered_clearance_date')->nullable();
            $table->dateTime('sold_date')->nullable();
            $table->dateTime('settled_date')->nullable();
            $table->dateTime('dispatched_or_collected_date')->nullable();
            $table->dateTime('withdrawn_date')->nullable();
        });

        Schema::table('item_lifecycles', function (Blueprint $table) {
            $table->string('action')->nullable()->after('status');
            $table->dateTime('entered_date')->nullable()->after('action');
            $table->dateTime('sold_date')->nullable()->after('entered_date');
            $table->dateTime('withdrawn_date')->nullable()->after('sold_date');
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
