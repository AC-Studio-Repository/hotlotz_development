<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsIntoDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('document_type')->nullable()->after('type')->comment('explainer, form, sop, roster');
            $table->string('file_name')->nullable()->after('document_type');
            $table->string('file_path')->nullable()->after('file_name');
            $table->string('full_path')->nullable()->after('file_path');
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
