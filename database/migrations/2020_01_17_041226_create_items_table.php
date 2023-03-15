<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable(); //*
            $table->integer('customer_id'); //*
            $table->integer('category_id'); //*
            $table->integer('country_id')->default(0)->nullable(); //*
            $table->integer('package_id')->default(0)->nullable(); //*
            $table->integer('lifecycle_id')->default(0)->nullable(); //*
            $table->integer('valuer_id')->default(0)->nullable(); //*
            $table->string('status')->nullable(); //*
            
            $table->string('title')->nullable(); //*
            $table->longText('long_description')->nullable(); //*

            // $table->bigInteger('item_number')->nullable(); //Lot Number
            $table->string('item_number')->nullable(); //Lot Number
            $table->string('permission_to_sell')->default('N')->nullable();
            $table->string('receipt_no')->nullable();
            $table->dateTime('end_time_utc')->nullable();
            $table->boolean('is_pro_photo_need')->default(0)->nullable();
            $table->integer('quantity')->default(1)->nullable();
            // $table->string('category_name')->nullable();
            // $table->integer('sub_category_id')->default(0)->nullable();
            $table->string('sub_category')->nullable();
            $table->longText('category_data')->nullable();
            $table->string('cataloguing_needed')->nullable();

            $table->decimal('low_estimate', 15, 2)->nullable();
            $table->decimal('high_estimate', 15, 2)->nullable();
            $table->decimal('reserve', 15, 2)->nullable();
            $table->decimal('opening_price', 15, 2)->nullable();
            $table->decimal('buy_it_now_price', 15, 2)->nullable();
            // $table->decimal('retail_price', 15, 2)->nullable()->default(0);
            $table->string('currency')->default('SGD')->nullable();
            $table->decimal('vat_tax_rate', 15, 2)->nullable();
            $table->decimal('buyers_premium_vat_rate', 15, 2)->nullable();
            $table->decimal('buyers_premium_percent', 15, 2)->nullable();
            $table->decimal('buyers_premium_ceiling', 15, 2)->nullable();
            $table->decimal('internet_surcharge_vat_rate', 15, 2)->nullable();
            $table->decimal('internet_surcharge_percent', 15, 2)->nullable();
            $table->decimal('internet_surcharge_ceiling', 15, 2)->nullable();
            $table->decimal('increment', 15, 2)->nullable();

            $table->string('sale_section')->nullable();
            $table->boolean('is_bulk')->default(0)->nullable();
            $table->boolean('artist_resale_rights')->default(0)->nullable();
            $table->integer('sequence_number')->nullable();
            $table->boolean('is_potentially_offensive')->nullable()->default(0);

            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->string('address4')->nullable();
            $table->string('town_city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('country_code')->nullable();
            $table->string('county_state')->nullable();

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
        Schema::dropIfExists('items');
    }
}
