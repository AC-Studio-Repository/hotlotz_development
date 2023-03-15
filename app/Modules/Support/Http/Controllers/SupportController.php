<?php

namespace App\Modules\Support\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class SupportController extends Controller
{
    public function main(){
        return view('support::index');
    }

    public function verifyCustomerEmail(Request $request){
        $customer = Customer::withTrashed()->where('ref_no',$request->ref_no)->first();
        $customer['email_verified_at'] = Date::now();
        $customer->save();

        flash()->success(__(':name\'s email has been verified', ['name' => $customer->fullname]));
        return redirect(route('support.index'));
    }
}
