<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnToAboutusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('about_us', function (Blueprint $table) {
            $table->dropColumn('contact_country_1');
            $table->dropColumn('email_1');
            $table->dropColumn('contact_country_2');
            $table->dropColumn('email_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('about_us', function (Blueprint $table) {
            //
        });
    }
}
