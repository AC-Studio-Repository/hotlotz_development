<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullFilePathColumnAndRenameFilePathToImageToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('strategic_partners', function (Blueprint $table) {
            $table->text('full_file_path')->nullable()->after('file_path');
            $table->renameColumn('file_path', 'image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('strategic_partners', function (Blueprint $table) {
            //
        });
    }
}
