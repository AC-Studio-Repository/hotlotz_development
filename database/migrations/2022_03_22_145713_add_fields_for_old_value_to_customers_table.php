<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsForOldValueToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->text('old_title')->nullable();            
            $table->text('old_salutation')->nullable();
            $table->text('old_firstname')->nullable();
            $table->text('old_lastname')->nullable();
            $table->text('old_fullname')->nullable();
            $table->text('old_email')->nullable();
            $table->boolean('old_reg_behalf_company')->nullable();
            $table->text('old_company_name')->nullable();
            $table->integer('old_country_of_residence')->nullable();
            $table->boolean('old_buyer_gst_registered')->nullable();
            $table->text('old_dialling_code')->nullable();
            $table->text('old_phone')->nullable();
            $table->boolean('old_reg_gst_sg')->nullable();
            $table->boolean('old_seller_gst_registered')->nullable();
            $table->text('old_sg_uen_number')->nullable();
            $table->text('old_gst_number')->nullable();
            $table->text('old_bank_account_number')->nullable();
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
