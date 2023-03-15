<?php

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Models\Customer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/email/verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:customer')->only('show');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }



    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return Auth::guard('customer')->user()->hasVerifiedEmail()
            ? redirect($this->redirectTo)
            : view('auth.verify');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $customer = Customer::find($request->route('id'));

        if ($customer != null) {
            if ($customer->hasVerifiedEmail()) {
                return redirect(route('customer.invite.accept'));
            }

            if (hash_equals((string) $request->route('hash'), sha1($customer->getEmailForVerification()))) {
                $customer->markEmailAsVerified();

                if ($customer->has_agreement == 0) {
                    return redirect(route('customer.invite.accept'));
                }

                if ($customer->has_agreement == 1) {
                    return redirect('/my-description')->withSuccess('Thank you for verifying your email.');
                }
            }
        }

        return redirect('/');
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if (Auth::guard('customer')->user()->hasVerifiedEmail()) {
            return redirect($this->redirectTo);
        }

        Auth::guard('customer')->user()->sendEmailVerificationNotification();

        return redirect($this->redirectTo)->with('resent', true);
    }

    public function saleroomCustomerVerify(Request $request)
    {
        $customer = Customer::find($request->route('id'));

        if ($customer != null) {
            
            $hash_id = $customer->id;
            if(isset($customer->hash_id) && $customer->hash_id != null){
                $hash_id = $customer->hash_id;
            }

            if ($customer->hasVerifiedEmail()) {
                return redirect(route('customer.saleroom_register', $hash_id));
            }

            if (hash_equals((string) $request->route('hash'), sha1($customer->getEmailForVerification()))) {
                $customer->markEmailAsVerified();

                return redirect(route('customer.saleroom_register', $hash_id));
            }
        }

        return redirect('/');
    }
}
