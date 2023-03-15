<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNeededFieldsIntoAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->string('status')->nullable()->after('title');
            $table->boolean('is_approved')->default(0)->after('status');
            $table->boolean('is_published')->default(0)->after('is_approved');
            $table->uuid('sr_auction_id')->nullable()->after('is_published');
            $table->string('sr_category_name')->nullable()->after('sr_auction_id');
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
