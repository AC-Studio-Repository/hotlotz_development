<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameLearnMoreToLinkInHomepageBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homepage_banners', function (Blueprint $table) {
            $table->renameColumn('learn_more', 'link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homepage_banners', function (Blueprint $table) {
            $table->renameColumn('link', 'learn_more');
        });
    }
}
