<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemFeeStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_fee_structures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('item_id');
            $table->string('fee_type')->comment('sales_commission, fixed_cost_sales_fee, hotlotz_owned_stock');
            
            $table->string('sales_commission')->nullable();
            $table->string('fixed_cost_sales_fee')->nullable();
            $table->string('hotlotz_owned_stock')->nullable();

            $table->string('performance_commission_setting')->nullable();
            $table->string('performance_commission')->nullable();

            $table->string('minimum_commission_setting')->nullable();
            $table->string('minimum_commission')->nullable();

            $table->string('insurance_fee_setting')->nullable();
            $table->string('insurance_fee')->nullable();

            $table->string('listing_fee_setting')->nullable();
            $table->string('listing_fee')->nullable();

            $table->string('unsold_fee_setting')->nullable();
            $table->string('unsold_fee')->nullable();

            $table->string('withdrawal_fee_setting')->nullable();
            $table->string('withdrawal_fee')->nullable();

            $table->integer('created_by')->default(1);
            $table->integer('updated_by')->default(1);
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_fee_structures');
    }
}
