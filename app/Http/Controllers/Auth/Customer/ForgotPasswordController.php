<?php

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Modules\SysConfig\Models\SysConfig;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:customer');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    // public function showLinkRequestForm()
    // {
    //     return view('auth.passwords.email');
    // }
   /**
     * Show the reset email form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm(){
        $today_open_time = SysConfig::getTodayOpenTime();

        return view('auth.passwords.customer.email',[
            'title' => 'Customer Forgot Password',
            'passwordEmailRoute' => 'customer.password.email',
            'today_open_time' => $today_open_time
        ]);
    }

    public function showAcceptInvite(){
        $today_open_time = SysConfig::getTodayOpenTime();

        return view('auth.passwords.customer.invite',[
            'title' => 'Customer Forgot Password',
            'passwordEmailRoute' => 'customer.password.email',
            'today_open_time' => $today_open_time
        ]);
    }

     /**
     * password broker for admin guard.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker(){
        return Password::broker('customers');
    }


    /**
     * Get the guard to be used during authentication
     * after password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard(){
        return Auth::guard('customer');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $customer = Customer::where('email', $request->email)->first();
        if($customer != null) $customer->markAsAgreed();

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }
}
