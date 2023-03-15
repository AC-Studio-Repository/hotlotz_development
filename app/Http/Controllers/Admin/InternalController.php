<?php

namespace App\Http\Controllers\Admin;

use Konekt\User\Models\User;

use App\ThirdPartyPaymentAlert;

use Yajra\Datatables\Datatables;
use Illuminate\Support\HtmlString;
use App\Http\Controllers\Controller;
use Laravel\Horizon\Contracts\JobRepository;

class InternalController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth');
    }

    /**
    * Create a new controller instance.
    *
    * @param  \Laravel\Horizon\Contracts\JobRepository  $jobs
    * @return void
    */

    public function checkAuctionQueue(JobRepository $jobs)
    {
        $jobs = $jobs->getRecent()->map(function ($job) {
            $job->payload = json_decode($job->payload);

            return $job;
        })->values();

        return view('admin.internal.check-auction-queue',[
            'jobs' => $jobs
        ]);
    }

    public function thirdPartyPaymentAlert()
    {
        return view('admin.internal.third-party-payment-alert');
    }

    public function thirdPartyPaymentAlertData()
    {
        $type = request()->type;
        if ($type == 'archive') {
            $data = ThirdPartyPaymentAlert::onlyTrashed()->with('customer')->select('*');
        }else{
            $data = ThirdPartyPaymentAlert::with('customer')->select('*');
        }
        return Datatables::of($data)
            ->addColumn('client', function ($eachData) {
                 return new HtmlString('<a target="_blank" href="'.route('customer.customers.show', $eachData->customer->id).'">'. $eachData->customer->fullname.' ('. $eachData->customer->ref_no .')</a>');
            })
            ->addColumn('legal_name', function ($eachData) {
                 return $eachData->customer->legal_name ?? 'N/A';
            })
            ->addColumn('card_holder_name', function ($eachData) {
                if($eachData->paymentData()){
                    return $eachData->paymentData()->billing_details->name;
                }
                return 'N/A';
            })
            ->addColumn('card_detail', function ($eachData) {
                if ($eachData->paymentData()) {
                    $method = $eachData->paymentData();
                    return ucfirst($method->card->brand).' - Ending '. $method->card->last4 .' - '.$method->card->exp_month . '/' . $method->card->exp_year;
                }
                return 'N/A';
            })
            ->addColumn(
                'action',
                function ($eachData) {
                    $type = request()->type;
                    if ($type == 'archive') {
                        $restoreButton = '<a href="'.url('third-party-payment-alert/restore', $eachData->id).'" class="btn btn-xs btn-outline-warning btn-show-on-tr-hover float-left mb-1"> Restore</a>';

                        return $restoreButton;
                    }else{
                        $deleteButton = '<a href="'.url('third-party-payment-alert/delete', $eachData->id).'" class="btn btn-xs btn-outline-warning btn-show-on-tr-hover float-left mb-1"> No action require</a>';
                        $mailTo = '<a href="mailto:'.$eachData->customer->email.'" class="btn btn-xs btn-outline-info btn-show-on-tr-hover float-left mb-1"> Send Email</a>';

                        return $deleteButton.$mailTo;

                    }
                }
            )
            ->make(true);
    }

    public function thirdPartyPaymentAlertDelete($id)
    {
        $thirdPartyPaymentAlert = ThirdPartyPaymentAlert::find($id);
        $thirdPartyPaymentAlert->delete();

        flash()->success(__('This item move to archive'));

        return redirect()->back();
    }

    public function thirdPartyPaymentAlertRestore($id)
    {
        $thirdPartyPaymentAlert = ThirdPartyPaymentAlert::withTrashed()->where('id',$id)->first();
        $thirdPartyPaymentAlert->restore();

        flash()->success(__('This item move to Active'));

        return redirect()->back();
    }
}