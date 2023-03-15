<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUploadedFilenameUploadedFilePathToHowToBuyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('how_to_buy', function (Blueprint $table) {
            $table->string('uploaded_filename')->nullable();
            $table->string('uploaded_filen_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('how_to_buy', function (Blueprint $table) {
            //
        });
    }
}
