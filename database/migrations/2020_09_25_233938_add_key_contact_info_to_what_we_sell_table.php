<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeyContactInfoToWhatWeSellTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE what_we_sell MODIFY COLUMN category_id INT (11) AFTER description");

        Schema::table('what_we_sell', function (Blueprint $table) {
            $table->integer('team_member_id')->unsigned()->default(0)->after('category_id');
            $table->string('key_contact_name')->after('team_member_id')->nullable();
            $table->text('key_contact_position')->after('key_contact_name')->nullable();
            $table->string('key_contact_email')->after('key_contact_position')->nullable();
            $table->string('key_contact_image')->after('key_contact_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('what_we_sell', function (Blueprint $table) {
            //
        });
    }
}
