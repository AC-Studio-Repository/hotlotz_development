<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('auctions');
        Schema::create('auctions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('legeacy_id')->default(0)->nullable();
            $table->string('auction_created_date_time_utc')->nullable();
            $table->boolean('time_is_already_utc')->default(0)->nullable();
            $table->string('created_by_user')->nullable();
            $table->string('type');
            $table->string('title')->nullable();
            $table->uuid('client_id')->nullable();
            $table->longText('auction_listings')->nullable();
            $table->string('timezone_id')->nullable();
            $table->boolean('card_required')->default(0)->nullable();
            $table->string('address1')->nullable();
            $table->string('town_city')->nullable();
            $table->string('country_state_name')->nullable();
            $table->string('post_code')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('currency')->nullable();
            $table->string('paddle_seed')->nullable();
            $table->enum('approval_type', ['Automatic', 'Manual'])->default('Manual');
            $table->text('important_information')->nullable();
            $table->text('terms')->nullable();
            $table->text('shipping_info')->nullable();
            $table->string('telephone_number',30)->nullable();
            $table->string('website')->default('https://www.hotlotz.com');
            $table->string('email',100)->nullable();
            $table->string('confirmation_email',100)->nullable();
            $table->string('registration_email',100)->nullable();
            $table->string('payment_receive_email',100)->nullable();
            $table->string('increment_set_name',20)->default('10s')->nullable();
            $table->decimal('minimum_deposite', 15, 2)->default(0)->nullable();
            $table->boolean('automatic_deposite')->default(0)->nullable();
            $table->boolean('automatic_refund')->default(0)->nullable();
            $table->integer('vat_rate')->default(20)->nullable();
            $table->decimal('buyers_premium_vat_rate', 15, 2)->default(0.2)->nullable();
            $table->decimal('internet_surcharge_vat_rate', 15, 2)->default(0.2)->nullable();
            $table->integer('buyers_premium')->default(0)->nullable();
            $table->integer('internet_surcharge_rate')->default(5)->nullable();
            $table->text('winner_notification_note')->nullable();

            $table->datetime('timed_start')->nullable();
            $table->datetime('timed_first_lot_ends')->nullable();
            
            $table->longText('sale_dates')->nullable();
            $table->longText('viewing_dates')->nullable();
            $table->longText('auction_card_types')->nullable();
            $table->boolean('piece_meal')->default(0)->nullable();
            $table->boolean('publish_post_sale_results')->default(0)->nullable();
            $table->decimal('international_debit_card_fixed_fee', 15, 2)->default(0)->nullable();
            $table->decimal('international_debit_card_percentage_fee', 15, 2)->default(0)->nullable();
            $table->longText('international_debit_card_fee_excluded_country_list')->nullable();
            $table->boolean('projected_spend_required')->default(0)->nullable();
            $table->longText('linked_auctions')->nullable();
            $table->decimal('atg_commission', 15, 2)->default(0)->nullable();
            $table->decimal('atg_commission_ceiling', 15, 2)->default(0)->nullable();
            $table->string('clients_auction_id')->nullable();
            $table->string('hammer_excess')->nullable();
            $table->boolean('hide_venue_address_for_lot_locations')->default(0)->nullable();
            $table->boolean('advanced_time_bidding_enabled')->default(0)->nullable();

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
        Schema::dropIfExists('auctions');
    }
}
