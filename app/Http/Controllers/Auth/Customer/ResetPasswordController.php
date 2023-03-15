<?php

namespace App\Http\Controllers\Auth\Customer;

use DB;
use Auth;
use Password;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Modules\Customer\Models\Customer;
use Illuminate\Auth\Events\PasswordReset;
use App\Modules\SysConfig\Models\SysConfig;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/sign-in';

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
     * Show the reset password form.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
        $today_open_time = SysConfig::getTodayOpenTime();

        return view('auth.passwords.customer.reset', [
            'title' => 'Customer Reset Password',
            'passwordUpdateRoute' => 'customer.password.update',
            'token' => $token,
            'today_open_time' => $today_open_time
        ]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    protected function broker()
    {
        return Password::broker('customers');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('customer');
    }

    public function updatePassword(Request $request)
    {
        if (helper('recaptcha')->v3(isset($_POST['recaptcha_response']) ? $_POST['recaptcha_response'] : '')) {
            $this->validate($request, [
                'password' => 'required|confirmed|min:6',
            ]);

            $password = $request->password;
            $token = $request->token;
            $tokenData = DB::table('password_resets')
             ->where('email', $request->email)->first();

            if (is_null($tokenData) || !Hash::check($token, $tokenData->token)) {
                return redirect(route('customer.password.request'))
                    ->withError('Whoops! Token verification failed, please try again.');
            }

            $customer = Customer::where('email', $tokenData->email)->first();
            if (!$customer) {
                return redirect(route('customer.password.request'))
                    ->withError('Whoops! Request email is not found, please try again.');
            }

            $customer->password = $password;
            $customer->hash_password = Hash::make($password);
            $customer->save();

            //do we log the user directly or let them login and try their password for the first time ? if yes
            Auth::login($customer);

            // If the user shouldn't reuse the token later, delete the token
            DB::table('password_resets')->where('email', $customer->email)->delete();

            return redirect(route('sign-in'))->with('success', 'Thank you. Your password has been reset. Please sign in to continue.');
        } else {
            return redirect()->back()
                    ->withInput()
                    ->withError('Whoops! reCAPTCHA verification failed, please try again.');
        }
    }
}
