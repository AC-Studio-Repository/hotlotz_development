<?php

use Illuminate\Support\Facades\Schema;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCustomerRefNoInCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $customers = Customer::withTrashed()->get();
        if ($customers->count() < 500) {
            foreach ($customers as $key => $customer) {
                $customer->ref_no = $customer->generateRefNo('a', $customer->id + 500);
                $customer->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
