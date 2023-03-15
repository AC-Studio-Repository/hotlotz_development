<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsIntoCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('title')->nullable()->after('type');
            $table->string('ref_no')->after('title');
            $table->string('password')->after('phone');
            $table->string('hash_password')->comment('SHA1HashedPassword')->after('password');
            $table->string('website')->nullable()->after('company_name');
            $table->string('fax_number')->nullable()->after('website');
            $table->string('vat_number')->nullable()->after('fax_number');
            $table->string('platform_code')->nullable()->after('vat_number');
            $table->string('time_zone')->nullable()->after('platform_code');
            $table->boolean('email_verified')->default(0)->after('time_zone');
            $table->boolean('phone_number_verified')->default(0)->after('email_verified');

            $table->string('salutation',6)->after('is_active');
            $table->string('fullname',255)->nullable()->after('salutation');
            $table->string('id_number',50)->nullable()->after('fullname');
            $table->string('address1',255)->nullable()->after('id_number');
            $table->string('address2',255)->nullable()->after('address1');
            $table->string('address3',255)->nullable()->after('address2');
            $table->string('city',255)->nullable()->after('address3');
            $table->string('county',255)->nullable()->after('city');
            $table->integer('country_id')->nullable()->after('county');
            $table->string('state',255)->nullable()->after('country_id');
            $table->string('postal_code',20)->nullable()->after('state');
            $table->string('mobile_phone',22)->nullable()->after('postal_code');
            $table->boolean('display_internal_note')->default(0)->after('mobile_phone');
            $table->text('internal_note')->nullable()->after('display_internal_note');
            // $table->integer('account_manager')->nullable()->after('internal_note');

            // $table->boolean('alert_catalogues')->default(0)->after('salutation');
            // $table->boolean('occasional_details')->default(0)->after('salutation');
            // $table->boolean('selected_occasional_details')->default(0)->after('salutation');

            #Seller Details
            $table->decimal('fixed_commission_amount', 15, 2)->nullable()->after('internal_note');
            $table->string('sellers_commission')->nullable()->after('fixed_commission_amount');
            $table->boolean('override_category')->default(0)->after('sellers_commission');
            $table->string('sellers_commission_type')->nullable()->after('override_category'); # Percentage/Fixed
            $table->decimal('sellers_commission_amount', 15, 2)->nullable()->after('sellers_commission_type');
            $table->string('vat_rate')->nullable()->after('sellers_commission_amount');
            $table->boolean('withhold_vat')->default(0)->after('vat_rate');

            #Bank Details
            $table->string('bank_account_name')->nullable()->after('withhold_vat');
            $table->string('bank_account_number')->nullable()->after('bank_account_name');
            $table->string('sort_code')->nullable()->after('bank_account_number');
            $table->string('payment_type')->nullable()->after('sort_code');
            $table->string('cheque_payable_name')->nullable()->after('payment_type');
            $table->string('iban')->nullable()->after('cheque_payable_name');
            $table->string('swift')->nullable()->after('iban');
            $table->string('account_currency')->nullable()->after('swift');
            $table->string('bank_name')->nullable()->after('account_currency');
            $table->text('bank_address')->nullable()->after('bank_name');
            $table->text('note_to_appear_on_statement')->nullable()->after('bank_address');

            #Buyer Details
            $table->string('buyer_number')->nullable()->after('note_to_appear_on_statement');
            $table->boolean('export_buyer')->default(0)->after('buyer_number');
            $table->string('buyer_premium_override')->nullable()->after('export_buyer');
            $table->string('dealers_collectors_invoice')->nullable()->after('buyer_premium_override');
            $table->text('note_to_appear_on_invoice')->nullable()->after('dealers_collectors_invoice');
            $table->integer('buyers_premium')->nullable()->after('note_to_appear_on_invoice');

            #Marketing
            $table->boolean('marketing_preference',255)->default(0)->after('buyers_premium');
            $table->string('category_interests',255)->nullable()->after('marketing_preference');

            #Documents
            $table->string('documents',255)->nullable()->after('category_interests');

            $table->integer('created_by')->default(1)->after('documents');
            $table->integer('updated_by')->default(1)->after('created_by');
            $table->integer('deleted_by')->nullable()->after('updated_by');
            $table->softDeletes()->after('updated_at');
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
