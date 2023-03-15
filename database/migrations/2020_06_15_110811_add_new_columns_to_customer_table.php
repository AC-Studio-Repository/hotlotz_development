<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('sg_uen_number')->default(0)->nullable();
            $table->boolean('reg_gst_sg')->default(0)->nullable();
            $table->integer('gst_number')->default(0)->nullable();
            $table->boolean('marketing_auction')->default(0)->nullable();
            $table->boolean('marketing_marketplace')->default(0)->nullable();
            $table->boolean('marketing_chk_events')->default(0)->nullable();
            $table->boolean('marketing_chk_congsignment_valuation')->default(0)->nullable();
            $table->boolean('marketing_hotlotz_quarterly')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
