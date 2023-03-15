<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIcDetailsFieldsForItemFeeStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_fee_structures', function (Blueprint $table) {
            $table->string('ic_details')->nullable()->after('withdrawal_fee');
            $table->string('ic_amount')->nullable()->after('ic_details');
            $table->integer('ic_commissioner')->nullable()->after('ic_amount');
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
