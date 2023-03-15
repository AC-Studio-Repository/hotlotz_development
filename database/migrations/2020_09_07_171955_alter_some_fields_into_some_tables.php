<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSomeFieldsIntoSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('is_new')->default('N')->nullable()->change();
            $table->string('is_pro_photo_need')->default('N')->nullable()->change();
            $table->string('is_reserve')->default('Y')->nullable()->change();
            $table->string('is_hotlotz_own_stock')->default('N')->nullable()->change();
            $table->string('is_dimension')->default('N')->nullable()->change();
            $table->string('is_weight')->default('N')->nullable()->change();
            $table->string('is_tree_planted')->default('N')->nullable()->change();
            $table->string('is_cataloguing_approved')->default('N')->nullable()->change();
            $table->string('is_valuation_approved')->default('N')->nullable()->change();
            $table->string('is_fee_structure_needed')->default('Y')->nullable()->change();
        });

        Schema::table('item_lifecycles', function (Blueprint $table) {
            $table->string('is_indefinite_period')->default('N')->nullable()->change();
        });

        Schema::table('auction_items', function (Blueprint $table) {
            $table->string('is_lot_ended')->default('N')->nullable()->change();
        });

        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn(['is_approved','is_published','is_closed','is_submitted','is_ready_invoice','is_invoiced']);
        });

        Schema::table('auctions', function (Blueprint $table) {
            $table->string('is_approved')->default('N')->nullable()->after('status');
            $table->string('is_published')->default('N')->nullable()->after('is_approved');
            $table->string('is_closed')->default('N')->nullable()->after('is_published');
            $table->string('is_submitted')->default('N')->nullable()->after('is_closed');
            $table->string('is_ready_invoice')->default('N')->nullable()->after('is_submitted');
            $table->string('is_invoiced')->default('N')->nullable()->after('is_ready_invoice');
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
