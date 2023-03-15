<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalInfoFieldsIntoCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('legal_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('occupation')->nullable();
            $table->string('citizenship_type')->nullable(); // (single/dual)
            $table->string('citizenship_one')->nullable();//country
            $table->string('citizenship_two')->nullable();//country
            $table->string('id_type')->nullable(); // (nric, fin, passport)
            $table->string('nric')->nullable(); // (NRIC Number)
            $table->string('nric_document_ids')->nullable();
            $table->string('fin')->nullable(); // (FIN Number)
            $table->string('fin_document_ids')->nullable();
            $table->string('country_of_issue')->nullable();//country
            $table->string('passport')->nullable(); // (Passport Number)
            $table->date('passport_expiry_date')->nullable();
            $table->string('passport_document_ids')->nullable();
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
