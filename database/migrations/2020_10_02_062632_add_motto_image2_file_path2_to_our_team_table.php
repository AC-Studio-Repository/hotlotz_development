<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMottoImage2FilePath2ToOurTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('our_team', function (Blueprint $table) {
            $table->text('motto')->nullable();
            $table->string('profile_image2')->nullable();
            $table->string('full_path2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('our_team', function (Blueprint $table) {
            //
        });
    }
}
