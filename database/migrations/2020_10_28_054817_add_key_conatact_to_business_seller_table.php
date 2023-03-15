<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeyConatactToBusinessSellerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_seller', function (Blueprint $table) {
            $table->integer('key_contact_1')->unsigned()->default(0)->after('blog_3');
            $table->integer('key_contact_2')->unsigned()->default(0)->after('key_contact_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_seller', function (Blueprint $table) {
            //
        });
    }
}
