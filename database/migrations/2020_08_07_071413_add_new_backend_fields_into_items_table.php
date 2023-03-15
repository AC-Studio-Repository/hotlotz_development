<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewBackendFieldsIntoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('is_new')->default(0)->nullable()->after('package_id');
            // $table->integer('approver_id')->default(0)->nullable()->after('valuer_id');
            // $table->boolean('is_approved')->default(0)->after('approver_id');
            // $table->dateTime('approval_date')->nullable()->after('is_approved');


            $table->boolean('is_dimension')->default(1)->nullable()->after('dimensions');
            $table->boolean('is_weight')->default(1)->nullable()->after('weight');
            $table->longText('internal_notes')->nullable()->after('additional_notes');
            $table->text('specific_condition_value')->nullable()->after('condition');

            $table->dateTime('saleroom_receipt_date')->nullable()->after('seller_agreement_signed_date');
            $table->dateTime('entered_auction1_date')->nullable()->after('saleroom_receipt_date');
            $table->dateTime('entered_auction2_date')->nullable()->after('entered_auction1_date');
            $table->dateTime('paid_date')->nullable()->after('settled_date');
            $table->dateTime('storage_date')->nullable()->after('withdrawn_date');
            $table->dateTime('declined_date')->nullable()->after('storage_date');

            //Dispatched Data
            $table->string('dispatched_person')->nullable()->after('dispatched_or_collected_date');
            $table->text('dispatched_remark')->nullable()->after('dispatched_person');

            //Email Flags
            $table->string('pending_flag')->nullable();
            $table->string('declined_flag')->nullable();
            $table->string('in_auction_flag')->nullable();
            $table->string('in_marketplace_flag')->nullable();
            $table->string('sold_flag')->nullable();
            $table->string('settled_flag')->nullable();
            $table->string('paid_flag')->nullable();
            $table->string('withdrawn_flag')->nullable();
            $table->string('storage_flag')->nullable();
            $table->string('to_be_collected_flag')->nullable();
            $table->string('dispatched_flag')->nullable();

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
