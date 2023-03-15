<?php

namespace App\Http\Controllers\Auth\Customer;

use DB;
use Auth;
use Hash;
use Lang;
use Route;
use Cookie;
use Stripe\Stripe;
use App\Helpers\NHelpers;
use Illuminate\Http\Request;
use App\Rules\MultipleUnique;
use App\Events\CustomerCreatedEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Customer\Models\Customer;
use App\Modules\SysConfig\Models\SysConfig;
use App\Modules\EmailTemplate\Models\EmailTemplate;
use App\Modules\Customer\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Mail;

class LoginRegisterController extends Controller
{
    protected $customerRepository;
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->middleware('guest:customer', ['except' => ['logout', 'register', 'signIn', 'customer_register', 'customer_redirect_signin']]);
        $this->customerRepository = $customerRepository;
    }

    public function showLoginForm()
    {
        return view('pages.sign-in');
    }

    public function login(Request $request)
    {
        // Validate the form data
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        // Attempt to log the user in
        if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // if successful, then redirect to their intended location
            if (isset($request->hid_query_string) && $request->hid_query_string != null) {
                $useremail = $request->email;
                $querystring = $request->hid_query_string;
                $redirect_url = $this->createJWTRedirectSSO($useremail, $querystring);

                return redirect()->intended($redirect_url);
            } else {
                return Redirect::intended('defaultpageafterlogin');
            }
        }
        // if unsuccessful, then redirect back to the login with the form data
        //  return redirect()->back()->withInput($request->only('email', 'remember'));

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => Lang::get('Incorrect email/password'),
            ]);
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        // return redirect('/home');
        if (Cookie::has('jwt')) {
            return redirect('/home')->withCookie(Cookie::forget('jwt'));
        } else {
            return redirect('/home');
        }
    }

    public function register()
    {
        if (Auth::guard("customer")->user() != null) {
            return redirect('/my-description');
        } else {
            $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id');
            $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');
            $today_open_time = SysConfig::getTodayOpenTime();
            $stripeCountries = resolve('App\Repositories\CountryRepository');
            $stripeCountries = $stripeCountries->getAllCountriesForStripe();
            $salutations = NHelpers::getSalutations();

            $data = [
                'countries' => $countries,
                'today_open_time' => $today_open_time,
                'country_codes' => $country_codes,
                'stripeCountries' => $stripeCountries,
                'salutations' => $salutations,
            ];

            return view('pages.register', $data);
        }
    }

    public function signIn(Request $request)
    {
        if (Auth::guard("customer")->user() != null) {
            if (isset($request->tenantId) && isset($request->redirectUrl) && isset($request->returnUrl)) {
                $redirect_url = $this->createJWTRedirectSSO(
                    Auth::guard('customer')->user()->email,
                    \Str::replaceFirst($request->url()."?", "", $request->fullUrl())
                );
                return redirect($redirect_url);
            } else {
                return redirect('/my-description');
            }
        } else {
            $today_open_time = SysConfig::getTodayOpenTime();
            $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');
            $data = [
                'today_open_time' => $today_open_time,
                'country_codes' => $country_codes,
            ];

            return view('pages.sign-in', $data);
        }
    }

    public function customer_register(Request $request)
    {
        if (helper('recaptcha')->v3(isset($_POST['recaptcha_response']) ? $_POST['recaptcha_response'] : '')) {
            $validatedData = $request->validate([
                'title' => 'required',
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => ['required', 'string', 'email', 'max:100', new MultipleUnique('customers', 'email', false, [])],
                'password' => 'required|string|min:8|max:100|confirmed',
                'password_confirmation' => 'required|string|min:8|max:100',
                'phone' => 'required',
                'reg_behalf_company'  => 'sometimes|required',
                'company_name' => 'required_if:reg_behalf_company,1',
                'sg_uen_number' => 'required_if:reg_behalf_company,1',
                'reg_gst_sg'  => 'sometimes|required',
                'gst_number' => 'required_if:reg_gst_sg,1',
                'term_and_condition' => 'accepted',
                // 'sel_marketing_pref' => 'required'
            ]);

            DB::beginTransaction();
            try {
                $payload = $this->packData($request);
                // dd($payload);

                if ($request->reg_credit_card == 1 && $request->stripeToken) {
                    if ($request->reg_credit_card == 1 && $request->stripeToken) {
                        Stripe::setApiKey(setting('services.stripe.secret'));

                        $stripeCustomer = \Stripe\Customer::create(
                            [
                                'email' => $request->email,
                            ]
                        );

                        $payload['stripe_customer_id'] = $stripeCustomer->id;


                        \Stripe\Customer::update($stripeCustomer->id, [
                            'source' => $request->stripeToken,
                        ]);
                    }
                }

                // $customerRepository = resolve('App\Modules\Customer\Repositories\CustomerRepository');
                $customer = $this->customerRepository->create($payload);

                Mail::to($customer->email)
                ->send(new \App\Mail\User\Activate($customer));               
                        
                DB::commit();
                if(isset($customer->register_credit_cards[0]))
                {
                    $stripeClient = new \Stripe\StripeClient(
                        setting('services.stripe.secret')
                    );
                    $stripeClient->customers->updateSource(
                         $customer->stripe_customer_id,
                         $customer->register_credit_cards[0]->id,
                        ['metadata' => ['is_save' => true]]
                    );
                }
                // flash()->success(__(':name has been created', ['name' => $customer->getName()]));
                $flash_message = $customer->fullname . ' account has been created. Please sign in to your account!';
                return redirect()->route('sign-in')->withSuccess($flash_message);
            } catch (\throwable $e) {
                \Log::error($e);
                DB::rollback();
                return redirect()->back()->withInput()->withError('For some reason, you account cannot be created. Please try again.');
            }
        } else {
            return redirect()->back()
                    ->withInput()
                    ->withError('Whoops! reCAPTCHA verification failed, please try again.');
        }
    }


    protected function packData($request)
    {
        $payload['title'] = $request->title;
        $payload['salutation'] = $request->title;
        $payload['firstname'] = $request->firstname;
        $payload['lastname'] = $request->lastname;
        $payload['fullname'] = $request->firstname.' '.$request->lastname;
        $payload['email'] = $request->email;
        $payload['password'] = $request->password;
        $payload['hash_password'] = Hash::make($request->password);
        $payload['phone'] = $request->phone;

        //TODO - Fix : Have to save both for Correspondence Address to work.
        // $payload['country_id'] = $request->country_id;
        $payload['country_of_residence'] = $request->country_id;

        if ($request->country_id == '702') {
            $payload['buyer_gst_registered'] = 1;
        } else {
            $payload['buyer_gst_registered'] = 0;
        }

        $payload['dialling_code'] = $request->dialling_code;
        $ref_no = Customer::getCustomerRefNo();
        $payload['ref_no'] = $ref_no;

        // if ($request->companyRegister == 'yes') {
        //     $payload['type'] = 'organization';
        // } else {
        //     $payload['type'] = 'individual';
        // }

        if ($request->reg_behalf_company == 1) {
            $payload['reg_behalf_company'] = $request->reg_behalf_company;
            $payload['type'] = 'organization';
        }else{
            $payload['type'] = 'individual';
        }

        $payload['company_name'] = ($request->company_name)?$request->company_name:null;

        $payload['sg_uen_number'] = ($request->sg_uen_number)?$request->sg_uen_number:null;

        $payload['reg_gst_sg'] = ($request->reg_gst_sg) ? 1 : 0;
        $payload['seller_gst_registered'] = ($request->reg_gst_sg) ? 1 : 0;

        $payload['gst_number'] = ($request->gst_number)?$request->gst_number:null;
        $payload['marketing_auction'] = ($request->marketing_auction)?1:0;
        $payload['marketing_marketplace'] = ($request->marketing_marketplace)?1:0;
        $payload['marketing_chk_events'] = ($request->marketing_chk_events)?1:0;
        $payload['marketing_chk_congsignment_valuation'] = ($request->marketing_chk_congsignment_valuation)?1:0;
        $payload['marketing_hotlotz_quarterly'] = ($request->marketing_hotlotz_quarterly)?1:0;

        $payload['has_agreement'] = 1;
        $payload['exclude_marketing_material'] = $request->exclude_marketing_material;

        return $payload;
    }

    public function customer_redirect_signin(Request $request)
    {
        try {
            $querystring = json_decode($request->queryString);
            $useremail = $request->login_user;

            $redirect_url = $this->createJWTRedirectSSO($useremail, $querystring);

            return response()->json(array('status' => '1','message'=>$redirect_url));
        } catch (\Exception $e) {
            \Log::error($e);
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    protected function createJWTRedirectSSO($useremail, $querystring)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $phone_number = Auth::guard('customer')->user()->dialling_code.Auth::guard('customer')->user()->phone;
        $email_verified = Auth::guard('customer')->user()->email_verified;
        $phone_number_verified = Auth::guard('customer')->user()->phone_number_verified;
        $atg_title = Auth::guard('customer')->user()->title;
        $given_name = Auth::guard('customer')->user()->firstname;
        $family_name = Auth::guard('customer')->user()->lastname;
        $atg_country_code = Auth::guard('customer')->user()->country_of_residence;

        $email  = Auth::guard('customer')->user()->email;
        $user_email = $useremail;
        $epoch_time_seconds = time();

        // Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        $issuedat = time();
        $expire_date = date("Y-m-d H:i:s", strtotime('+24 hours'));
        $expiry = strtotime($expire_date);

        $payload = [
            'sub' => $user_email,
            'iss' => config('thesaleroom.iss'),
            'aud' => 'whitelabel',
            'phone_number' => $phone_number,
            'exp' => $expiry,
            'iat' => $issuedat,
            'atg_tenant_id' => config('thesaleroom.atg_tenant_id'),
            'given_name' => $given_name,
            'family_name' => $family_name,
            'atg_title' => $atg_title,
            'atg_country_code' => $atg_country_code,
            'email' => $email,
            'email_verified' => $email_verified,
            'phone_number_verified' => $phone_number_verified
        ];

        // Create token payload as a JSON string
        $payload = json_encode($payload);

        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $secret = config('thesaleroom.jwt_secret');

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Create JWT
        $jwt_token_WL = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        // echo $jwt_token_WL;

        $cookie = Cookie::make('jwt', $jwt_token_WL);

        // $h = hash_hmac('sha256', $customer_id, $secret, $epoch_time_seconds, true);
        // $redirect_url = "https://example-identity-server.com/platforms/SR/authenticate?c=".$customer_id."&h=".$h."&t=".$epoch_time_seconds;
        $url = "https://secure-login.globalauctionplatform.com/signin-sso?";
        $token = "jwt=".$jwt_token_WL;
        $param_query_string = "&".$querystring;

        $redirect_url = $url.$token.$param_query_string;

        return $redirect_url;
    }


    public function saleroomRegister($hash_id)
    {
        $customer = Customer::where('hash_id',$hash_id)->first();

        $today_open_time = SysConfig::getTodayOpenTime();
        $salutations = NHelpers::getSalutations();
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();
        $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');
        $stripeCountries = resolve('App\Repositories\CountryRepository');
        $stripeCountries = $stripeCountries->getAllCountriesForStripe();

        $data = [
            'customer' => $customer,
            'today_open_time' => $today_open_time,
            'salutations' => $salutations,
            'countries' => $countries,
            'country_codes' => $country_codes,
            'stripeCountries' => $stripeCountries,
        ];
        return view('pages.register_saleroom', $data);
    }

    public function updateSaleroomCustomer($customer_id, Request $request)
    {
        // dd($customer_id);
        if (helper('recaptcha')->v3(isset($_POST['recaptcha_response']) ? $_POST['recaptcha_response'] : '')) {

            $validatedData = $request->validate([
                'salutation' => 'required',
                'password' => 'required|string|min:8|max:100|confirmed',
                'password_confirmation' => 'required|string|min:8|max:100',
                'phone' => 'required',
                'reg_behalf_company'  => 'sometimes|required',
                'company_name' => 'required_if:reg_behalf_company,1',
                'sg_uen_number' => 'required_if:reg_behalf_company,1',
                'reg_gst_sg'  => 'sometimes|required',
                'gst_number' => 'required_if:reg_gst_sg,1',
                'term_and_condition' => 'accepted',
            ]);

            DB::beginTransaction();
            try {
                $payload = $this->packDataSaleroomCustomer($request);
                // dd($payload);

                if ($request->reg_credit_card == 1 && $request->stripeToken) {
                    if ($request->reg_credit_card == 1 && $request->stripeToken) {
                        Stripe::setApiKey(setting('services.stripe.secret'));

                        $stripeCustomer = \Stripe\Customer::create(
                            [
                                'email' => $request->email,
                            ]
                        );

                        $payload['stripe_customer_id'] = $stripeCustomer->id;

                        \Stripe\Customer::update($stripeCustomer->id, [
                            'source' => $request->stripeToken,
                        ]);
                    }
                }


                // $customerRepository = resolve('App\Modules\Customer\Repositories\CustomerRepository');
                $updated_result = $this->customerRepository->update($customer_id, $payload);

                if($updated_result){
                    $customer = Customer::find($customer_id);

                    if(isset($customer->register_credit_cards[0]))
                    {
                        $stripeClient = new \Stripe\StripeClient(
                            setting('services.stripe.secret')
                        );
                        $stripeClient->customers->updateSource(
                             $customer->stripe_customer_id,
                             $customer->register_credit_cards[0]->id,
                            ['metadata' => ['is_save' => true]]
                        );
                    }
                    DB::commit();

                    $flash_message = $customer->fullname . ' account has been updated. Please sign in to your account!';
                    return redirect()->route('sign-in')->withSuccess($flash_message);
                }

                DB::rollback();
                return redirect()->back()->withInput()->withError('For some reason, your account cannot be updated. Please try again.');

            } catch (\throwable $e) {
                \Log::error($e->getMessage());
                DB::rollback();
                return redirect()->back()->withInput()->withError('For some reason, your account cannot be updated. Please try again.');
            }

        } else {
            return redirect()->back()
                    ->withInput()
                    ->withError('Whoops! reCAPTCHA verification failed, please try again.');
        }
    }

    protected function packDataSaleroomCustomer($request)
    {
        $payload['password'] = $request->password;
        $payload['hash_password'] = Hash::make($request->password);
        $payload['title'] = $request->salutation;
        $payload['salutation'] = $request->salutation;
        $payload['dialling_code'] = $request->dialling_code;

        if ($request->reg_behalf_company == 1) {
            $payload['reg_behalf_company'] = $request->reg_behalf_company;
            $payload['type'] = 'organization';
        }else{
            $payload['type'] = 'individual';
        }

        $payload['company_name'] = isset($request->company_name)?$request->company_name:null;
        $payload['sg_uen_number'] = isset($request->sg_uen_number)?$request->sg_uen_number:null;
        $payload['reg_gst_sg'] = isset($request->reg_gst_sg) ? 1 : 0;
        $payload['seller_gst_registered'] = isset($request->reg_gst_sg) ? 1 : 0;
        $payload['gst_number'] = isset($request->gst_number)?$request->gst_number:null;
        $payload['has_agreement'] = 1;

        return $payload;
    }

    public function checkPhoneFieldByEmail(Request $request)
    {
        if($request->get('email'))
        {
            $email = $request->get('email');
            $customer = Customer::where('email', $email)->first();
            if ($customer) {
                if ($customer->phone == null || $customer->phone == '' ||
                $customer->dialling_code == null || $customer->dialling_code == '') {
                    return response()->json(array('status'=>'fail'));
                } else {
                    return response()->json(array('status'=>'success'));
                }
            }
        }
        return response()->json(array('status'=>'success'));
    }

    public function updatePhoneByEmail(Request $request)
    {
        if($request->get('email') && $request->get('phone'))
        {
            $email = $request->get('email');
            $customer = Customer::where('email', $email)->first();
            if($customer){
                $customer->phone = $request->get('phone');
                $customer->dialling_code = $request->get('dialling_code');
                $customer->save();
            }
            return response()->json(array('status'=>'success'));
        }
        return response()->json(array('status'=>'fail'));
    }

}