<?php

namespace App\Modules\AdminEmail\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\AdminEmail\Http\Requests\StoreAdminEmailRequest;
use App\Modules\AdminEmail\Http\Requests\UpdateAdminEmailRequest;
use App\Modules\AdminEmail\Http\Repositories\AdminEmailRepository;
use App\Modules\AdminEmail\Models\AdminEmail;
use DB;

class AdminEmailController extends Controller
{
    protected $adminEmailRepository;
    public function __construct(AdminEmailRepository $adminEmailRepository){
        $this->adminEmailRepository = $adminEmailRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sellwithus_emails = AdminEmail::where('type','swu')->get();
        $bankaccount_emails = AdminEmail::where('type','bau')->get();
        $profileupdate_emails = AdminEmail::where('type','profile')->get();
        $mpsolditems_emails = AdminEmail::where('type','mp_sold_items')->get();
        $itemsmovedtostorage_emails = AdminEmail::where('type','items_moved_to_storage')->get();
        $salescontract_emails = AdminEmail::where('type','sales_contract')->get();
        $bank_paynow_checkout_emails = AdminEmail::where('type','bank_paynow_checkout')->get();
        $kyc_update_emails = AdminEmail::where('type','kyc')->get();

        $data = [
            'sellwithus_emails' => $sellwithus_emails,
            'bankaccount_emails' => $bankaccount_emails,
            'profileupdate_emails' => $profileupdate_emails,
            'mpsolditems_emails' => $mpsolditems_emails,
            'itemsmovedtostorage_emails' => $itemsmovedtostorage_emails,
            'salescontract_emails' => $salescontract_emails,
            'bank_paynow_checkout_emails' => $bank_paynow_checkout_emails,
            'kyc_update_emails' => $kyc_update_emails,
        ];
        return view('admin_email::index',$data);
    }

    public function save(Request $request)
    {
        try {
            // prepare variables
            $inputs = $request->all();
            // dd($inputs);

            //For Sell With Us Weekly Emails
            if(isset($inputs['email']) && isset($inputs['email']['swu']) && count($inputs['email']['swu']) > 0) {
                $swu_inputs = $inputs['email']['swu'];
                foreach ($swu_inputs as $key => $swu) {
                    $data = [
                        'type' => 'swu',
                        'email' => $swu,
                    ];
                    AdminEmail::create($data);
                }
            }

            //For Bank Account Update Alert Emails
            if(isset($inputs['email']) && isset($inputs['email']['bau']) && count($inputs['email']['bau']) > 0) {
                $bau_inputs = $inputs['email']['bau'];
                foreach ($bau_inputs as $key => $bau) {
                    $data = [
                        'type' => 'bau',
                        'email' => $bau,
                    ];
                    AdminEmail::create($data);
                }
            }

            //For Profile Update Alert Emails
            if(isset($inputs['email']) && isset($inputs['email']['profile']) && count($inputs['email']['profile']) > 0) {
                $profile_inputs = $inputs['email']['profile'];
                foreach ($profile_inputs as $key => $profile) {
                    $data = [
                        'type' => 'profile',
                        'email' => $profile,
                    ];
                    AdminEmail::create($data);
                }
            }

            //For Marketplace Sold Items Emails
            if(isset($inputs['email']) && isset($inputs['email']['mp_sold_items']) && count($inputs['email']['mp_sold_items']) > 0) {
                $mp_sold_items_inputs = $inputs['email']['mp_sold_items'];
                foreach ($mp_sold_items_inputs as $key => $mp_sold_items) {
                    $data = [
                        'type' => 'mp_sold_items',
                        'email' => $mp_sold_items,
                    ];
                    AdminEmail::create($data);
                }
            }

            //For Items moved to Storage Emails
            if(isset($inputs['email']) && isset($inputs['email']['items_moved_to_storage']) && count($inputs['email']['items_moved_to_storage']) > 0) {
                $items_moved_to_storage_inputs = $inputs['email']['items_moved_to_storage'];
                foreach ($items_moved_to_storage_inputs as $key => $items_moved_to_storage) {
                    $data = [
                        'type' => 'items_moved_to_storage',
                        'email' => $items_moved_to_storage,
                    ];
                    AdminEmail::create($data);
                }
            }

            //For SalesContractAlert Emails
            if(isset($inputs['email']) && isset($inputs['email']['sales_contract']) && count($inputs['email']['sales_contract']) > 0) {
                $sales_contract_inputs = $inputs['email']['sales_contract'];
                foreach ($sales_contract_inputs as $key => $sales_contract) {
                    $data = [
                        'type' => 'sales_contract',
                        'email' => $sales_contract,
                    ];
                    AdminEmail::create($data);
                }
            }

            //For Bank Transfer/PayNow Checkout Alert Emails
            if(isset($inputs['email']) && isset($inputs['email']['bank_paynow_checkout']) && count($inputs['email']['bank_paynow_checkout']) > 0) {
                $bank_paynow_checkout_inputs = $inputs['email']['bank_paynow_checkout'];
                foreach ($bank_paynow_checkout_inputs as $key => $bank_paynow_checkout) {
                    $data = [
                        'type' => 'bank_paynow_checkout',
                        'email' => $bank_paynow_checkout,
                    ];
                    AdminEmail::create($data);
                }
            }

            // dd($inputs);
            //For KYC Update Alert Emails
            if(isset($inputs['email']) && isset($inputs['email']['kyc']) && count($inputs['email']['kyc']) > 0) {
                $kyc_inputs = $inputs['email']['kyc'];
                foreach ($kyc_inputs as $key => $kyc) {
                    $data = [
                        'type' => 'kyc',
                        'email' => $kyc,
                    ];
                    AdminEmail::create($data);
                }
            }

            flash()->success(__('Admin Email has been saved'));
            return redirect()->route('admin_email.admin_emails.index')->with('success', 'Admin Email Saved Successfully!');

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            // return redirect()->back()->withInput();
            return redirect()->route('admin_email.admin_emails.index')->with('failed', 'Admin Email Saving Failed!');
        }
    }


    public function destroy(AdminEmail $admin_email)
    {
        try {

            AdminEmail::where('id', $admin_email->id)->forceDelete();

            return response()->json([ 'status'=>'success', 'message' => 'Admin Email has been deleted']);

        } catch (\Exception $e) {
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }

}