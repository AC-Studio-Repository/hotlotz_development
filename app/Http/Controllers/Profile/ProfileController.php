<?php

namespace App\Http\Controllers\Profile;

use DB;
use Auth;
use Hash;
use View;
use Cookie;
use Response;
use Stripe\Stripe;
use App\Models\Country;
use App\Helpers\NHelpers;
use App\Helpers\MenuHelper;
use App\Models\GeneralInfo;
use Illuminate\Http\Request;
use App\Helpers\StorageHelper;
use App\Modules\Item\Models\Item;
use App\Http\Controllers\Controller;
use App\Repositories\ItemRepository;
use App\Repositories\BannerRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\CountryRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\CategoryRepository;
use App\Modules\Customer\Models\Customer;
use App\Modules\Item\Models\ItemLifecycle;
use App\Repositories\FavouritesRepository;
use App\Modules\SysConfig\Models\SysConfig;
use App\Repositories\CommunicationRepository;
use App\Events\Admin\SalesContractAlertEvent;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\Customer\Models\CustomerDocument;
use App\Events\Item\SellerAgreementConfirmationEvent;
use App\Modules\Customer\Repositories\CustomerRepository;
use App\Modules\Customer\Repositories\CustomerInterestRepository;
use App\Modules\Item\Http\Repositories\ItemRepository as ItemItemRepository;

class ProfileController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    protected $itemItemRepository;
    protected $itemRepository;
    protected $bannerRepository;
    protected $profileRepository;
    protected $communicationRepository;
    protected $favouritesRepository;
    protected $categoryRepository;
    protected $customerRepository;
    protected $countryRepository;
    protected $customerInterestRepository;

    public function __construct(
        ItemItemRepository $itemItemRepository,
        ItemRepository $itemRepository,
        BannerRepository $bannerRepository,
        ProfileRepository $profileRepository,
        CommunicationRepository $communicationRepository,
        FavouritesRepository $favouritesRepository,
        CategoryRepository $categoryRepository,
        CountryRepository $countryRepository,
        CustomerRepository $customerRepository,
        CustomerInterestRepository $customerInterestRepository
    ) {
        $this->middleware(['auth:customer','verified']);

        $this->itemItemRepository = $itemItemRepository;
        $this->itemRepository = $itemRepository;
        $this->bannerRepository = $bannerRepository;
        $this->profileRepository = $profileRepository;
        $this->communicationRepository = $communicationRepository;
        $this->favouritesRepository = $favouritesRepository;
        $this->categoryRepository = $categoryRepository;
        $this->customerRepository = $customerRepository;
        $this->countryRepository = $countryRepository;
        $this->customerInterestRepository = $customerInterestRepository;
    }

    public function myDescription()
    {
        $fullname = "";
        $account_number = "";
        $email = "";
        $complete_status = '';

        $today_open_time = SysConfig::getTodayOpenTime();
        $show_list = $this->profileRepository->getShowLists();
        $btn_group = $this->profileRepository->getShowButtons();

        $complete_status = $btn_group['complete_status'];
        $show_btn_group = $btn_group['btn_group'];

        if (Auth::guard('customer')->check()) {
            $fullname = Auth::guard('customer')->user()->firstname.' '.Auth::guard('customer')->user()->lastname;

            $email = Auth::guard('customer')->user()->email;
            $account_number = Auth::guard('customer')->user()->ref_no;
        }

        $data =  array();
        $data['greeting'] = 'Welcome to your Dashboard';
        $data['show_list'] = $show_list;
        $data['show_btn_group'] = $show_btn_group;
        $data['today_open_time']  =  $today_open_time;
        $data['fullname']  =  $fullname;
        $data['complete_status'] = $complete_status;
        $data['fullname'] = $fullname;
        $data['email'] = $email;
        $data['account_number'] = $account_number;

        return view('pages.profile.my-description', $data);
    }


    public function myAuction()
    {
        $today_open_time = SysConfig::getTodayOpenTime();
        $data = [
            'today_open_time' => $today_open_time,
        ];
        return view('pages.my-auctions', $data);
    }

    public function myPaperwork()
    {
        $today_open_time = SysConfig::getTodayOpenTime();
        $customer_id = Auth::guard('customer')->user()->id;

        $myconsignmentItems = $this->profileRepository->getMyConsignmentItems($customer_id);

        $statuses = [
            '' => 'All',
            Item::_SWU_ => 'Under Review',
            Item::_PENDING_ => 'Being Prepared For Sale', //including 'Pending' and 'Pending In Auction'
            'sold_in_auction' => 'Sold In Auction', //including 'Sold' and 'Paid'
            'sold_in_marketplace' => 'Sold In Marketplace', //including 'Sold' and 'Paid'
            Item::_SETTLED_ => Item::_SETTLED_,
            Item::_IN_AUCTION_ => 'On Sale In Auction',
            Item::_IN_MARKETPLACE_ => 'On Sale In Marketplace',
            Item::_DECLINED_ => Item::_DECLINED_,
            'unsold_in_storage' => 'Unsold - Awaiting Collection',
            'unsold_dispatched' => 'Unsold - Returned',
            Item::_WITHDRAWN_ => Item::_WITHDRAWN_,
            // Item::_ITEM_RETURNED_ => Item::_ITEM_RETURNED_,
        ];

        $data = [
            'today_open_time' => $today_open_time,
            'myconsignmentItems' => $myconsignmentItems,
            'statuses' => $statuses,
        ];

        return view('pages.profile.my-consignment', $data);
    }

    public function myPaperworkFilter(Request $request)
    {
        try {
            $customer_id = Auth::guard('customer')->user()->id;
            $status = $request->status;
            // dd($status);

            $myconsignmentItems = $this->profileRepository->getMyConsignmentItems($customer_id, $status);

            $returnHTML = view('pages.profile.my_consignment_items', [
                'myconsignmentItems' => $myconsignmentItems,
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Filter Successfully.', 'html'=>$returnHTML));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e));
        }
    }

    public function myPersonal()
    {
        $title = 'My Profile';
        $salutaion = '';
        $firstname = '';
        $lastname = '';
        $email = '';
        $phone = '';
        $country_id = '';
        $company_name = '';
        $sg_uen_number = '';
        $reg_gst_sg = '';
        $my_profile = '';
        $display_phone = '';

        $today_open_time = SysConfig::getTodayOpenTime();
        // $my_profile = $this->bannerRepository->getRandomImage();
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id');

        $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');
        $salutations = NHelpers::getSalutations();

        if (Auth::guard('customer')->check()) {
            if (Auth::guard('customer')->user()->title == 'R_N_S') {
                $salutaion = 'Rather not say';
            } else {
                $salutaion = Auth::guard('customer')->user()->title;
            }
            $firstname = Auth::guard('customer')->user()->firstname;
            $lastname = Auth::guard('customer')->user()->lastname;
            $email = Auth::guard('customer')->user()->email;
            $phone = Auth::guard('customer')->user()->phone;

            if (Auth::guard('customer')->user()->country_of_residence != null) {
                $country = Country::where('id', '=', Auth::guard('customer')->user()->country_of_residence)->first();
                $country_id = $country->name;
            }

            $company_name = Auth::guard('customer')->user()->company_name;
            $sg_uen_number = (Auth::guard('customer')->user()->sg_uen_number == 0) ? '' : Auth::guard('customer')->user()->sg_uen_number;
            $reg_gst_sg = (Auth::guard('customer')->user()->reg_gst_sg == 1) ? 'Yes' : 'No';
            $my_profile = Auth::guard('customer')->user()->image_path;
            $my_dialling_code = Auth::guard('customer')->user()->dialling_code;
            $display_phone = $my_dialling_code.' '.$phone;
        }

        if ($my_profile == null) {
            $my_profile = asset('/images/generic_photo.png');
        }

        $data = [
            'title' => $title,
            'my_profile' => $my_profile,
            'today_open_time' => $today_open_time,
            'countries' => $countries,
            'salutaion' => $salutaion,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'phone' => $phone,
            'country_id' => $country_id,
            'company_name' => $company_name,
            'sg_uen_number' => $sg_uen_number,
            'reg_gst_sg' => $reg_gst_sg,
            'country_codes' => $country_codes,
            'display_phone' => $display_phone,
            'my_dialling_code' => $my_dialling_code,
            'salutations' => $salutations
        ];
        return view('pages.profile.my-personal', $data);
    }

    public function myShipping()
    {
        $firstname = '';
        $lastname = '';
        $today_open_time = SysConfig::getTodayOpenTime();
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id');

        $customer_id = Auth::guard('customer')->user()->id;

        $address_count = 0;
        $addresses = $this->profileRepository->getCutomerAddress($customer_id);

        $correspondence_address = $this->profileRepository->getCorrespondenceAddress($customer_id);

        if (!empty($addresses)) {
            $address_count = $addresses->count();
        }

        if (Auth::guard('customer')->check()) {
            $firstname = Auth::guard('customer')->user()->firstname;
            $lastname = Auth::guard('customer')->user()->lastname;
        }

        $data = [
            'today_open_time' => $today_open_time,
            'countries' => $countries,
            'addresses' => $addresses,
            'address_count' => $address_count,
            'correspondence_address' => $correspondence_address,
            'firstname' => $firstname,
            'lastname' => $lastname
        ];
        return view('pages.profile.my-shipping', $data);
    }

    public function myCredit()
    {
        $today_open_time = SysConfig::getTodayOpenTime();
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id');
        $customer_id = Auth::guard('customer')->user()->id;
        $stripeCountries = $this->countryRepository->getAllCountriesForStripe();

        if (Auth::guard('customer')->user()->stripe_customer_id == null) {
            Stripe::setApiKey(setting('services.stripe.secret'));

            $stripeCustomer = \Stripe\Customer::create(
                [
                    'email' => Auth::guard('customer')->user()->email,
                ]
            );
            Auth::guard('customer')->user()->stripe_customer_id = $stripeCustomer->id;
            Auth::guard('customer')->user()->save();
        }
        $addresses = $this->profileRepository->getCutomerAddress($customer_id);
        $payment_methods = Auth::guard('customer')->user()->credit_cards;

        $data = [
            'today_open_time' => $today_open_time,
            'countries' => $countries,
            'addresses' => $addresses,
            'stripeCountries' => $stripeCountries,
            'payment_methods' => $payment_methods
        ];
        return view('pages.profile.my-credit', $data);
    }

    public function myBank()
    {
        $bank_name = '';
        $bank_account_name = '';
        $bank_account_number = '';
        $bank_country_id = '';
        $bank_country = '';
        $swift_code = '';
        $account_currency = '';
        $bank_additional_note = '';
        $bank_address = '';
        $bank_country_name = '';

        $today_open_time = SysConfig::getTodayOpenTime();
        $countries = DB::table('countries')->where('id', '!=', 702)->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id');

        if (Auth::guard('customer')->check()) {
            $bank_name = Auth::guard('customer')->user()->bank_name;
            $bank_account_name = Auth::guard('customer')->user()->bank_account_name;
            $bank_account_number = Auth::guard('customer')->user()->bank_account_number;
            $bank_country_id = Auth::guard('customer')->user()->bank_country_id;
            if ($bank_country_id > 0) {
                $bank_country_data = Country::where('id', '=', Auth::guard('customer')->user()->bank_country_id)->first();
                $bank_country_name = $bank_country_data->name;
            }
            $bank_country = Auth::guard('customer')->user()->bank_country;
            $swift_code = Auth::guard('customer')->user()->swift;
            $account_currency = Auth::guard('customer')->user()->account_currency;
            $bank_additional_note = Auth::guard('customer')->user()->bank_additional_note;
            $bank_address = Auth::guard('customer')->user()->bank_address;
        }

        $data = [
            'today_open_time' => $today_open_time,
            'countries' => $countries,
            'bank_name' => $bank_name,
            'bank_account_name' => $bank_account_name,
            'bank_account_number' => $bank_account_number,
            'bank_country_id' => $bank_country_id,
            'bank_country' => $bank_country,
            'swift_code' => $swift_code,
            'account_currency' => $account_currency,
            'bank_additional_note' => $bank_additional_note,
            'bank_address' => $bank_address,
            'bank_country_name' => $bank_country_name
        ];
        return view('pages.profile.my-bank', $data);
    }

    public function myBankTwo()
    {
        $today_open_time = SysConfig::getTodayOpenTime();
        $data = [
            'today_open_time' => $today_open_time,
        ];
        return view('pages.my-bank-2', $data);
    }

    public function myPreference()
    {
        $marketing_auction = '';
        $marketing_marketplace = '';
        $marketing_chk_events = '';
        $marketing_chk_congsignment_valuation = '';
        $marketing_hotlotz_quarterly = '';

        $customer_id = Auth::guard('customer')->user()->id;
        $communications = $this->communicationRepository->getCommunicationPreferences();
        // $interests = $this->itemRepository->getWhatWeSellCategories();
        $interests = $this->categoryRepository->getAllCategories();
        $today_open_time = SysConfig::getTodayOpenTime();

        $customer_interests = $this->profileRepository->getCutomerIntrests($customer_id);

        if (Auth::guard('customer')->check()) {
            $marketing_auction = Auth::guard('customer')->user()->marketing_auction;
            $marketing_marketplace = Auth::guard('customer')->user()->marketing_marketplace;
            $marketing_chk_events = Auth::guard('customer')->user()->marketing_chk_events;
            $marketing_chk_congsignment_valuation = Auth::guard('customer')->user()->marketing_chk_congsignment_valuation;
            $marketing_hotlotz_quarterly = Auth::guard('customer')->user()->marketing_hotlotz_quarterly;
        }

        $data = [
            'today_open_time' => $today_open_time,
            'communications' => $communications,
            'interests' => $interests,
            'marketing_auction' => $marketing_auction,
            'marketing_marketplace' => $marketing_marketplace,
            'marketing_chk_events' => $marketing_chk_events,
            'marketing_chk_congsignment_valuation' => $marketing_chk_congsignment_valuation,
            'marketing_hotlotz_quarterly' => $marketing_hotlotz_quarterly,
            'customer_interests' => $customer_interests
        ];
        return view('pages.profile.my-preference', $data);
    }

    public function myPreferenceEdit()
    {
        $marketing_auction = '';
        $marketing_marketplace = '';
        $marketing_chk_events = '';
        $marketing_chk_congsignment_valuation = '';
        $marketing_hotlotz_quarterly = '';

        $customer_id = Auth::guard('customer')->user()->id;
        $communications = $this->communicationRepository->getCommunicationPreferences();
        // $interests = $this->itemRepository->getWhatWeSellCategories();
        $interests = $this->categoryRepository->getAllCategories();
        $today_open_time = SysConfig::getTodayOpenTime();

        $customer_interests = $this->profileRepository->getCutomerIntrests($customer_id);

        if (Auth::guard('customer')->check()) {
            $marketing_auction = Auth::guard('customer')->user()->marketing_auction;
            $marketing_marketplace = Auth::guard('customer')->user()->marketing_marketplace;
            $marketing_chk_events = Auth::guard('customer')->user()->marketing_chk_events;
            $marketing_chk_congsignment_valuation = Auth::guard('customer')->user()->marketing_chk_congsignment_valuation;
            $marketing_hotlotz_quarterly = Auth::guard('customer')->user()->marketing_hotlotz_quarterly;
        }

        $data = [
            'today_open_time' => $today_open_time,
            'communications' => $communications,
            'interests' => $interests,
            'marketing_auction' => $marketing_auction,
            'marketing_marketplace' => $marketing_marketplace,
            'marketing_chk_events' => $marketing_chk_events,
            'marketing_chk_congsignment_valuation' => $marketing_chk_congsignment_valuation,
            'marketing_hotlotz_quarterly' => $marketing_hotlotz_quarterly,
            'customer_interests' => $customer_interests
        ];
        return view('pages.profile.my_preference_edit', $data);
    }

    public function sellerAgreement()
    {
        $customer_id = 0;
        if (Auth::guard('customer')->check()) {
            $customer_id = Auth::guard('customer')->user()->id;
        }

        $today_open_time = SysConfig::getTodayOpenTime();

        $item_list = Item::where('customer_id', $customer_id)
                ->where('permission_to_sell', '!=', 'Y')
                ->where('is_valuation_approved', 'Y')
                ->where('is_fee_structure_approved', 'Y')
                ->orderBy('items.name', 'asc')
                ->orderBy('items.created_at', 'desc')
                ->pluck('name', 'id')
                ->all();

        $pending_approval_items = Item::where('items.customer_id', $customer_id)
        ->where('permission_to_sell', '!=', 'Y')
        ->where('is_valuation_approved', 'Y')
        ->where('is_fee_structure_approved', 'Y')
        ->leftJoin('item_images', function ($join) {
            $join->on('item_images.id', '=', DB::raw('
                (SELECT item_images.id FROM item_images
                WHERE item_images.item_id = items.id
                and item_images.deleted_at is NULL
                LIMIT 1)'));
        })
        ->select('items.*', 'item_images.file_name', 'item_images.full_path')
        ->orderBy('items.created_at', 'desc');

        $pagination = 20;

        if (request()->limit) {
            $pagination = (request()->limit == 'all') ? $pending_approval_items->count() : request()->limit;
        }

        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id');

        if (Auth::guard('customer')->user()->bank_account_number == null && Auth::guard('customer')->user()->bank_name == null && Auth::guard('customer')->user()->bank_account_name == null) {
            $bank_condition = 1;
        } else {
            $bank_condition = 0;
        }
        $data = [
            'id' => $customer_id,
            'countries' => $countries,
            'bank_condition' => $bank_condition,
            'today_open_time' => $today_open_time,
            'item_list' => $item_list,
            'pending_approval_items' => $pending_approval_items->paginate((int)$pagination)->appends(request()->except('page')),
        ];

        return view('pages.profile.seller_agreement', $data);
    }

    public function sellerAgreementDetail($item_id)
    {
        try {
            $item = Item::where('items.id', $item_id)->first();
            // dd($item->itemlifecycles);

            $fee_structure = [];
            $item_fs = $item->fee_structure;
            if (isset($item_fs)) {
                $seller_commission_label = "Seller's Commission";
                $seller_commission = 'None';
                if ($item->fee_type == 'sales_commission') {
                    $seller_commission = (float) $item_fs->sales_commission.'% exc. GST ('.number_format( (float) $item_fs->sales_commission * 1.08, 2, '.', '').'% inc. GST)';
                }
                if ($item->fee_type == 'fixed_cost_sales_fee') {
                    $seller_commission_label = "Fixed Cost Sales Fee";
                    $seller_commission = str_replace(["$", "%", "+"], '', $item_fs->fixed_cost_sales_fee).' SGD';
                }

                // $fee_structure['minimum_commission'] = '$40';
                // $fee_structure['performance_commission'] = '3%';
                // $fee_structure['insurance_fee'] = '1.5%';
                // $fee_structure['withrawal_fee'] = '$5';
                // $fee_structure['listing_fee'] = 'None';
                // $fee_structure['unsold_fee'] = 'None';


                $fee_structure['sales_commission'] = ($item_fs->sales_commission != null)?$item_fs->sales_commission:'20%';
                $fee_structure['fixed_cost_sales_fee'] = ($item_fs->fixed_cost_sales_fee != null)?str_replace(["$", "%", "+"], '', $item_fs->fixed_cost_sales_fee):'40';

                if ($item_fs->performance_commission_setting) {
                    $fee_structure['performance_commission'] = ($item_fs->performance_commission != null)?$item_fs->performance_commission:'3%';
                }
                if ($item_fs->minimum_commission_setting) {
                    $fee_structure['minimum_commission'] = ($item_fs->minimum_commission != null)?str_replace(["$", "%", "+"], '', $item_fs->minimum_commission):'40';
                }
                if ($item_fs->insurance_fee_setting) {
                    $fee_structure['insurance_fee'] = ($item_fs->insurance_fee != null)?$item_fs->insurance_fee:'1.5%';
                }
                if ($item_fs->listing_fee_setting) {
                    $fee_structure['listing_fee'] = ($item_fs->listing_fee != null)?str_replace(["$", "%", "+"], '', $item_fs->listing_fee):'None';
                }
                if ($item_fs->unsold_fee_setting) {
                    $fee_structure['unsold_fee'] = ($item_fs->unsold_fee != null)?str_replace(["$", "%", "+"], '', $item_fs->unsold_fee):'None';
                }
                if ($item_fs->withdrawal_fee_setting) {
                    $fee_structure['withdrawal_fee'] = ($item_fs->withdrawal_fee != null)?str_replace(["$", "%", "+"], '', $item_fs->withdrawal_fee):'60';
                }
            }

            $returnHTML = view('pages.profile.seller_agreement_detail', [
                'item' => $item,
                'seller_commission_label' => $seller_commission_label,
                'seller_commission' => $seller_commission,
                'fee_structure' => $fee_structure,
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Get Item Detail Successfully.', 'html'=>$returnHTML));
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function salesContract()
    {
        $customer_id = 0;
        if (Auth::guard('customer')->check()) {
            $customer_id = Auth::guard('customer')->user()->id;
        }

        $today_open_time = SysConfig::getTodayOpenTime();

        $item_list = Item::where('customer_id', $customer_id)
                ->where('permission_to_sell', 'Y')
                ->where('is_valuation_approved', 'Y')
                ->where('is_fee_structure_approved', 'Y')
                ->orderBy('items.name', 'asc')
                ->orderBy('items.created_at', 'desc')
                ->pluck('name', 'id')
                ->all();

        $cosigned_items = Item::where('items.customer_id', $customer_id)
            ->where('permission_to_sell', 'Y')
            ->where('is_valuation_approved', 'Y')
            ->where('is_fee_structure_approved', 'Y')
            ->leftJoin('item_images', function ($join) {
                $join->on('item_images.id', '=', DB::raw('
                    (SELECT item_images.id FROM item_images
                    WHERE item_images.item_id = items.id
                    and item_images.deleted_at is NULL
                    LIMIT 1)'));
            })
            ->select('items.*', 'item_images.file_name', 'item_images.full_path')
            ->orderBy('items.created_at', 'desc');

        $pagination = 20;

        if (request()->limit) {
            $pagination = (request()->limit == 'all') ? $cosigned_items->count() : request()->limit;
        }

        $data = [
            'id' => $customer_id,
            'today_open_time' => $today_open_time,
            'item_list' => $item_list,
            'cosigned_items' => $cosigned_items->paginate((int) $pagination)->appends(request()->except('page')),
        ];
        return view('pages.profile.sales_contract', $data);
    }

    public function permissionToSell(Request $request)
    {
        $customer_id = 0;
        if (Auth::guard('customer')->check()) {
            $customer_id = Auth::guard('customer')->user()->id;
        }

        DB::beginTransaction();
        try {
            if ($customer_id > 0) {
                $approver = Customer::find($customer_id);
                $fullname = ($approver->fullname != null && $approver->fullname != '')?$approver->fullname:($approver->firstname.' '.$approver->lastname);
                if($approver->type->value() == 'organization' && $approver->company_name != null && $approver->company_name != ''){
                    $fullname = $approver->company_name;
                }

                if (isset($request->item_id)) {
                    $count = 0;
                    $item_ids = [];
                    foreach ($request->item_id as $key => $id) {
                        $name = "permission_to_sell_".$id;
                        if (isset($request->$name)) {
                            $count ++;
                            $item_ids[] = $id;
                            $payload = [
                                'permission_to_sell'=>'Y',
                                'seller_agreement_signed_date'=>date('Y-m-d H:i:s'),
                                'sale_contract_approved_name'=>$fullname,
                            ];
                            $this->itemItemRepository->update($id, $payload, true, 'PermissionToSell');
                        }
                    }

                    if ($count > 0) {
                        \Log::channel('emailLog')->info('call SellerAgreementConfirmationEvent when '.$approver->ref_no.' approve sale contract');
                        event(new SellerAgreementConfirmationEvent($customer_id));

                        \Log::channel('emailLog')->info('call SalesContractAlertEvent when '.$approver->ref_no.' approve sale contract');
                        event(new SalesContractAlertEvent($item_ids));

                        DB::commit();
                        if($request->bank_condition == 1){//require bank
                            $msg = 'Thank you for approving your Sales Contract. Please enter your bank account details so we can settle your account promptly.';
                        }else{
                            $msg = 'Thank you for approving your Sales Contract. Please check that your bank account details are correct.';
                        }
                        return redirect(route('my-bank'))->with('success',$msg);
                    }
                }
            } else {
                DB::rollback();
                return redirect()->back()->withInput()->withError('This Customer is not exist in Hotlotz System');
            }

            return redirect(route('my-paperwork.seller_agreement'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withError($e->getMessage());
        }
    }

    public function customer_update_info(Request $request)
    {
        $customer_id = Auth::guard('customer')->user()->id;

        $payload = $this->customer_update_packData($request);
        try {
            $result = $this->customerRepository->update($customer_id, $payload);

            return redirect(route('my-personal'))->with('success', 'Thank you. Your profile has been updated.');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->withInput()->with(['error' => 'Your profile is not updated. Please try again later.']);
        }
    }

    protected function customer_update_packData($request)
    {
        $payload = [];
        $customer_id = Auth::guard('customer')->user()->id;

        //From My Personnel
        if ($request->hasFile('upload_image')) {
            $profile_image = $request->file('upload_image');
            $image_original_name = $profile_image->getClientOriginalName();


            $result = StorageHelper::store($path = 'public/customer/profile_image/'.$customer_id, array($profile_image), $wipeExisting=true);

            $filename = $result[0]['name'];
            $file_path = $result[0]['data'];

            $payload['image'] = $filename;
            $payload['image_path'] = $file_path;
        }

        if ($request->hasFile('upload_image_mobile')) {
            $profile_image = $request->file('upload_image_mobile');
            $image_original_name = $profile_image->getClientOriginalName();


            $result = StorageHelper::store($path = 'public/customer/profile_image/'.$customer_id, array($profile_image), $wipeExisting=true);

            $filename = $result[0]['name'];
            $file_path = $result[0]['data'];

            $payload['image'] = $filename;
            $payload['image_path'] = $file_path;
        }

        if ($request->title) {
            $payload['title'] = $request->title;
            $payload['salutation'] = $request->title;
        }

        if ($request->firstname) {
            $payload['firstname'] = $request->firstname;
            $payload['fullname'] = $request->firstname;
        }

        if ($request->lastname) {
            $payload['lastname'] = $request->lastname;
            $payload['fullname'] .= ' '.$request->lastname;
        }

        if ($request->email) {
            $payload['email'] = $request->email;
        }

        if (isset($request->reg_behalf_company)) {
            $payload['reg_behalf_company'] = $request->reg_behalf_company;
            if ($request->reg_behalf_company == 0) {
                $payload['type'] = 'individual';
            } else {
                $payload['type'] = 'organization';
            }
        }

        if ($request->company_name) {
            if (isset($request->reg_behalf_company) && $request->reg_behalf_company == 0) {
                $payload['company_name'] = null;
            } else {
                $payload['company_name'] = $request->company_name;
            }
        }

        if ($request->country_id) {
            $payload['country_of_residence'] = $request->country_id;
            if ($request->country_id == '702') {
                $payload['buyer_gst_registered'] = 1;
            } else {
                $payload['buyer_gst_registered'] = 0;
            }
        }

        if ($request->dialling_code) {
            $payload['dialling_code'] = $request->dialling_code;
        }

        if ($request->phone) {
            $payload['phone'] = $request->phone;
        }

        if (isset($request->reg_gst_sg)) {
            $payload['reg_gst_sg'] = $request->reg_gst_sg;
            $payload['seller_gst_registered'] = ($request->reg_gst_sg) ? 1 : 0;
        }

        if ($request->sg_uen_number) {
            if (isset($request->reg_behalf_company) && $request->reg_behalf_company == 0) {
                $payload['sg_uen_number'] = 0;
            } else {
                $payload['sg_uen_number'] = $request->sg_uen_number;
            }
        }

        if ($request->gst_number) {
            if (isset($request->reg_gst_sg) && $request->reg_gst_sg == 0) {
                $payload['gst_number'] = 0;
            } else {
                $payload['gst_number'] = $request->gst_number;
            }
        }

        return $payload;
    }

    public function add_customer_address_info(Request $request)
    {
        $address_id = $request->hid_new_address_id;
        $customer_id = Auth::guard('customer')->user()->id;
        $type = 'shipping';
        $payload = $this->customer_address_packData($request, $type);
        try {
            $is_primary = 0;
            $addresses = $this->profileRepository->getCutomerAddress($customer_id);

            if ($request->is_primary || empty($addresses)) {
                $is_primary = 1;
            }

            if ($is_primary == 1) {
                DB::table('customer_addresses')->where('customer_id', $customer_id)->where('is_primary', '=', "1")->update(['is_primary' => '0']);
            }

            $address_id = DB::table('addresses')->insertGetId($payload);

            $result = DB::table('customer_addresses')->insertGetId(
                ['customer_id' => $customer_id, 'address_id' => $address_id, 'is_primary' => $is_primary]
            );

            return redirect(route('my-shipping'))->with('success', 'Address has been added.');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->withInput()->with(['error' => 'Address is not added. Please try again later.']);
        }
    }

    protected function customer_address_packData($request, $type)
    {
        if ($request->address_nickname) {
            $payload['address_nickname'] = $request->address_nickname;
        }

        if ($request->firstname) {
            $payload['firstname'] = $request->firstname;
        }

        if ($request->lastname) {
            $payload['lastname'] = $request->lastname;
        }

        if ($request->address) {
            $payload['address'] = $request->address;
        }

        if ($request->city) {
            $payload['city'] = $request->city;
        }

        if ($request->postalcode) {
            $payload['postalcode'] = $request->postalcode;
        }

        if ($request->country_id) {
            $payload['country_id'] = $request->country_id;
        }

        if ($request->state) {
            $payload['state'] = $request->state;
        }

        if ($request->daytime_phone) {
            $payload['daytime_phone'] = $request->daytime_phone;
        }

        if ($request->delivery_instruction) {
            $payload['delivery_instruction'] = $request->delivery_instruction;
        }

        $payload['type'] = $type;

        return $payload;
    }

    public function update_customer_address_info(Request $request)
    {
        $address_id = $request->hid_edit_address_id;
        $customer_id = Auth::guard('customer')->user()->id;
        $type = 'shipping';
        $is_primary = 0;
        $payload = $this->update_address_packData($request, $type, $address_id);

        try {
            if ($request['is_primary_'.$address_id]) {
                $is_primary = 1;
                DB::table('customer_addresses')->where('customer_id', $customer_id)->where('is_primary', '=', "1")->update(['is_primary' => '0']);

                DB::table('customer_addresses')->where('id', '=', $address_id)->where('customer_id', '=', $customer_id)->update(['is_primary' => '1']);
            } else {
                $is_primary = 0;
            }

            $result = DB::table('addresses')->where('id', $address_id)->update($payload);

            // return response()->json(array('status' => '1','message'=>'Address has been updated.'));
            return redirect(route('my-shipping'))->with('success', 'Address has been updated.');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            // return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
            return redirect()->back()->withInput()->with(['error' => 'Address is not updated. Please try again later.']);
        }
    }

    protected function update_address_packData($request, $type, $address_id)
    {
        if ($request['address_nickname_'.$address_id]) {
            $payload['address_nickname'] = $request['address_nickname_'.$address_id];
        }

        if ($request['firstname_'.$address_id]) {
            $payload['firstname'] = $request['firstname_'.$address_id];
        }

        if ($request['lastname_'.$address_id]) {
            $payload['lastname'] = $request['lastname_'.$address_id];
        }

        if ($request['address_'.$address_id]) {
            $payload['address'] = $request['address_'.$address_id];
        }

        if ($request['city_'.$address_id]) {
            $payload['city'] = $request['city_'.$address_id];
        }

        if ($request['postalcode_'.$address_id]) {
            $payload['postalcode'] = $request['postalcode_'.$address_id];
        }

        if ($request['country_id_'.$address_id]) {
            $payload['country_id'] = $request['country_id_'.$address_id];
        }

        if ($request['state_'.$address_id]) {
            $payload['state'] = $request['state_'.$address_id];
        }

        if ($request['daytime_phone_'.$address_id]) {
            $payload['daytime_phone'] = $request['daytime_phone_'.$address_id];
        }

        if ($request['delivery_instruction_'.$address_id]) {
            $payload['delivery_instruction'] = $request['delivery_instruction_'.$address_id];
        }
        $payload['type'] = $type;

        return $payload;
    }

    public function delete_address(Request $request)
    {
        $address_id = $request->address_id;
        $customer_id = Auth::guard('customer')->user()->id;

        try {
            $is_primary = DB::table('customer_addresses')->where('address_id', '=', $address_id)->where('customer_id', '=', $customer_id)->where('is_primary', '=', 1)->get();

            if (!$is_primary->isEmpty()) {
                return response()->json(array('status' => '-1','message'=>'Can\'t delete primary address!'));
            } else {
                DB::table('customer_addresses')->where('address_id', $address_id)->delete();
                DB::table('addresses')->where('id', '=', $address_id)->delete();
                return response()->json(array('status' => '1','message'=>'Shipping Address has been deleted'));
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    public function update_customer_bank_info(Request $request)
    {
        if ($request->chk_country == 'other') {
            $validated = $request->validate([
                'bank_country_id' => 'required',
                'bank_name' => 'required',
                'bank_account_name' => 'required',
                'bank_account_number' => 'required',
                'swift' => 'required',
                'account_currency' => 'required'
            ]);
        } else {
            $validated = $request->validate([
                'bank_country_id' => 'required',
                'bank_name' => 'required',
                'bank_account_name' => 'required',
                'bank_account_number' => 'required'
            ]);
        }

        $customer_id = Auth::guard('customer')->user()->id;
        $payload = $this->customer_bank_packData($request);
        try {
            $result = $this->customerRepository->update($customer_id, $payload);
            return redirect()->back()->with('success', 'Your Bank Info has been updated.');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->withInput()->with(['error' => 'Your Bank Info is not updated. Please try again later.']);
        }
    }

    protected function customer_bank_packData($request)
    {
        //From My Bank
        $other_flag = false;
        if ($request->chk_country == 'other') {
            $other_flag = true;
        } else {
            $other_flag = false;
        }

        if ($request->chk_country) {
            $payload['bank_country'] = $request->chk_country;
        }

        if ($request->bank_name && $request->bank_name != '') {
            $payload['bank_name'] = $request->bank_name;
        }

        if ($request->bank_country_id) {
            if ($other_flag == false) {
                $payload['bank_country_id'] = 0;
            } else {
                $payload['bank_country_id'] = $request->bank_country_id;
            }
        }

        if ($request->bank_account_name && $request->bank_account_name != '') {
            $payload['bank_account_name'] = $request->bank_account_name;
        }

        if ($request->bank_account_number && $request->bank_account_number != '') {
            $payload['bank_account_number'] = $request->bank_account_number;
        }

        if ($request->swift && $request->swift != '') {
            if ($other_flag == false) {
                $payload['swift'] = null;
            } else {
                $payload['swift'] = $request->swift;
            }
        }

        if ($request->account_currency && $request->account_currency != '') {
            if ($other_flag == false) {
                $payload['account_currency'] = null;
            } else {
                $payload['account_currency'] = $request->account_currency;
            }
        }

        if ($request->bank_additional_note && $request->bank_additional_note != '') {
            if ($other_flag == false) {
                $payload['bank_additional_note'] = null;
            } else {
                $payload['bank_additional_note'] = $request->bank_additional_note;
            }
        }

        if ($request->bank_address && $request->bank_address != '') {
            if ($other_flag == false) {
                $payload['bank_address'] = null;
            } else {
                $payload['bank_address'] = $request->bank_address;
            }
        }

        return $payload;
    }

    public function update_customer_credit_info(Request $request)
    {
        $address_id = $request->hid_address_id;
        $customer_id = $request->hid_customer_id;
        $type = "billing";
        $payload = $this->customer_address_packData($request, $type);
        try {
            if ($address_id > 0) {
                $result = DB::table('addresses')->where('id', $address_id)->update($payload);
            } else {
                $address_id = DB::table('addresses')->insertGetId(
                    $payload
                );

                $result = DB::table('customer_addresses')->insertGetId(
                    ['customer_id' => $customer_id, 'address_id' => $address_id]
                );
            }
            return redirect(route('my-credit'))->with('success', 'Credit Info has been updated.');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->withInput()->with(['error' => 'Credit info is not updated. Please try again later.']);
        }
    }

    public function update_preference_info(Request $request)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $payload = $this->customer_preference_packData($request);
        try {
            $result = $this->customerRepository->update($customer_id, $payload);
            $this->profileRepository->deleteOldIntrests($customer_id);

            if ($request->chk_value) {
                foreach ($request->chk_value as $value) {
                    $payloadIntrest['customer_id'] = $customer_id;
                    $payloadIntrest['what_we_sell_id'] = (int)$value;

                    $result = $this->customerInterestRepository->create($payloadIntrest);
                }
            }
            return redirect(route('my-preference'))->with('success', 'Your Preference has been updated.');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->withInput()->with(['error' => 'Your Preference is not updated. Please try again later.']);
        }
    }

    protected function customer_preference_packData($request)
    {
        if ($request->marketing_auction) {
            $payload['marketing_auction'] = $request->marketing_auction;
        } else {
            $payload['marketing_auction'] = 0;
        }

        if ($request->marketing_marketplace) {
            $payload['marketing_marketplace'] = $request->marketing_marketplace;
        } else {
            $payload['marketing_marketplace'] = 0;
        }

        if ($request->marketing_chk_events) {
            $payload['marketing_chk_events'] = $request->marketing_chk_events;
        } else {
            $payload['marketing_chk_events'] = 0;
        }

        if ($request->marketing_chk_congsignment_valuation) {
            $payload['marketing_chk_congsignment_valuation'] = $request->marketing_chk_congsignment_valuation;
        } else {
            $payload['marketing_chk_congsignment_valuation'] = 0;
        }

        if ($request->marketing_hotlotz_quarterly) {
            $payload['marketing_hotlotz_quarterly'] = $request->marketing_hotlotz_quarterly;
        } else {
            $payload['marketing_hotlotz_quarterly'] = 0;
        }

        return $payload;
    }

    public function myMarketplace()
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $count = 0;

        $my_watchlist = $this->favouritesRepository->getMyWatchlist($customer_id);

        if (!empty($my_watchlist)) {
            $count = $my_watchlist->count();
        }

        $today_open_time = SysConfig::getTodayOpenTime();

        $data = [
            'today_open_time' => $today_open_time,
            'my_watchlist' => $my_watchlist,
            'list_count' => $count
        ];
        return view('pages.profile.my_marketplace', $data);
    }

    public function getMoreFavouriteItem(Request $request)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $offset = $request->offset;
        $limit = $request->limit;

        $watchlist = $this->favouritesRepository->getMyWatchlist($customer_id, $limit, $offset);

        $data = [
            'status'=>1,
            'ajax_data'=>$watchlist,
            'append' => true
        ];
        return json_encode($data);
    }

    public function myReceipt($type)
    {
        if (!in_array($type, ['auction', 'marketplace', 'miscellaneous', 'awaiting'])) {
            return abort(404);
        }
        $customer_id = Auth::guard('customer')->user()->id;

        $paperwork_data = $this->profileRepository->getMyReceiptByType($customer_id, $type);

        $today_open_time = SysConfig::getTodayOpenTime();

        $title = '';
        if ($type == 'auction') {
            $title = 'Auction Invoices';
        } elseif ($type == 'marketplace') {
            $title = 'Marketplace Invoices';
        } elseif ($type == 'miscellaneous') {
            $title = 'Miscellaneous Invoices';
        } else {
            $title = 'Invoices Awaiting Payment';
        }

        $data = [
            'today_open_time' => $today_open_time,
            'paperworks' => NHelpers::paginate($paperwork_data, 10),
            'title' => $title,
            'type' => $type,
        ];

        return view('pages.profile.my-receipt', $data);
    }

    public function saleroomReceipts()
    {
        $customer_id = Auth::guard('customer')->user()->id;

        $paperwork_data = $this->profileRepository->getMySettlement($customer_id);

        $today_open_time = SysConfig::getTodayOpenTime();

        $title = 'Saleroom Receipts';

        $data = [
            'today_open_time' => $today_open_time,
            'paperworks' => NHelpers::paginate($paperwork_data, 10),
            'title' => $title
        ];
        return view('pages.profile.my_salerooms', $data);
    }

    public function mySettlement()
    {
        $customer_id = Auth::guard('customer')->user()->id;

        $paperwork_data = $this->profileRepository->getMySettlement($customer_id);

        $today_open_time = SysConfig::getTodayOpenTime();

        $title = 'Settlement List';

        $data = [
            'today_open_time' => $today_open_time,
            'paperworks' => NHelpers::paginate($paperwork_data, 10),
            'title' => $title
        ];
        return view('pages.profile.my-settlement', $data);
    }

    public function generateInvoiceUrl($id)
    {
        $customerInvoice = new CustomerInvoice;
        $url = $customerInvoice->url($id);

        if ($url != null) {
            return redirect($url);
        } else {
            return abort(404);
        }
    }

    public function myReceiptCheckout($invoice_id)
    {
        $title = "Checkout";
        $menus = MenuHelper::getMenuInvoices();
        $customerInvoice = CustomerInvoice::where('invoice_id', $invoice_id)->first();
        $today_open_time = SysConfig::getTodayOpenTime();
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id');
        $address_count = 0;

        $customer_id = Auth::guard('customer')->user()->id;

        $addresses = $this->profileRepository->getCutomerAddress($customer_id);
        if (!empty($addresses)) {
            $address_count = $addresses->count();
        }
        $stripeCountries = $this->countryRepository->getAllCountriesForStripe();

        $data = [
            'title' => $title,
            'menus' => $menus,
            'total' => $customerInvoice->invoice_amount,
            'invoice_id' => $invoice_id,
            'today_open_time' => $today_open_time,
            'countries' => $countries,
            'addresses' => $addresses,
            'address_count' => $address_count,
            'stripeCountries' => $stripeCountries,
            'invoice_type' => $customerInvoice->invoice_type,
            'invoice_number' => $customerInvoice->invoice()->InvoiceNumber
        ];

        return view('pages.profile.receipt_pay_now', $data);
    }

    public function add_correspondence_address_info(Request $request)
    {
        $address_id = $request->hid_corr_address_id;
        $customer_id = Auth::guard('customer')->user()->id;
        $type = 'correspondence';
        $payload = $this->customer_address_packData($request, $type);

        DB::beginTransaction();
        try {
            $address_id = DB::table('addresses')->insertGetId($payload);

            $result = DB::table('customer_addresses')->insertGetId(
                ['customer_id' => $customer_id, 'address_id' => $address_id]
            );

            // TODO - need to reopen on April
            if ($payload['country_id'] == 702) {
                $payload['buyer_gst_registered'] = 1;
            } else {
                $payload['buyer_gst_registered'] = 0;
            }
            $payload['address1'] = $payload['address'];
            unset($payload['address']);
            if ($payload['postalcode']) {
                $payload['postal_code'] = $payload['postalcode'];
                unset($payload['postalcode']);
            }
            unset($payload['type']);
            unset($payload['firstname']);
            unset($payload['lastname']);

            $result = $this->customerRepository->update($customer_id, $payload);
            DB::commit();

            return redirect(route('my-shipping'))->with('success', 'Address Info has been added.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e->getMessage());
            return redirect()->back()->withInput()->with(['error' => 'Address info is not added. Please try again later.']);
        }
    }

    public function update_correspondence_address(Request $request)
    {
        $address_id = $request->hid_correspondence_addressid;
        $client_address_id = $request->hid_client_addressid;
        $customer_id = Auth::guard('customer')->user()->id;
        $type = 'correspondence';
        $payload = $this->update_correspondence_packData($request, $type, $address_id);
        // dd($address_id);

        DB::beginTransaction();
        try {
            if ($address_id > 0) {
                $result = DB::table('addresses')->where('id', $address_id)->update($payload);
            } else {
                $address_id = DB::table('addresses')->insertGetId($payload);

                $result = DB::table('customer_addresses')->insertGetId(
                    ['customer_id' => $customer_id, 'address_id' => $address_id]
                );
            }

            // TODO - need to reopen on April
            if ($payload['country_id'] == 702) {
                $payload['buyer_gst_registered'] = 1;
            } else {
                $payload['buyer_gst_registered'] = 0;
            }
            $payload['address1'] = $payload['address'];
            unset($payload['address']);
            if (isset($payload['postalcode'])) {
                $payload['postal_code'] = $payload['postalcode'];
                unset($payload['postalcode']);
            }
            unset($payload['type']);
            unset($payload['firstname']);
            unset($payload['lastname']);
            $result = $this->customerRepository->update($customer_id, $payload);
            DB::commit();

            return response()->json(array('status' => '1','message'=>'Correspondence Address has been updated.'));
            // return redirect(route('my-shipping'))->with('success', 'Correspondence Address has been updated.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e->getMessage());
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
            // return redirect()->back()->withInput()->with(['error' => 'Correspondence Address is not updated. Please try again later.']);
        }
    }

    protected function update_correspondence_packData($request, $type)
    {
        if ($request['firstname']) {
            $payload['firstname'] = $request['firstname'];
        }

        if ($request['lastname']) {
            $payload['lastname'] = $request['lastname'];
        }

        if ($request['address']) {
            $payload['address'] = $request['address'];
        }

        if ($request['city']) {
            $payload['city'] = $request['city'];
        }

        if ($request['postalcode']) {
            $payload['postalcode'] = $request['postalcode'];
        }

        if ($request['country_id']) {
            $payload['country_id'] = $request['country_id'];
        }

        if ($request['state']) {
            $payload['state'] = $request['state'];
        }

        $payload['type'] = $type;

        return $payload;
    }

    public function delete_correspondence_address(Request $request)
    {
        $address_id = $request->address_id;
        $customer_id = Auth::guard('customer')->user()->id;

        try {
            DB::table('customer_addresses')->where('address_id', $address_id)->delete();
            DB::table('addresses')->where('id', '=', $address_id)->delete();

            $result = $this->customerRepository->update($customer_id, [
                        'address1' => null,
                        'city' => null,
                        'state' => null,
                        'postal_code' => null
                    ]);

            return response()->json(array('status' => '1','message'=>'Correspondence Address has been deleted'));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    public function showAgreement()
    {
        return view('pages.agreement');
    }

    public function acceptAgreement()
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $customer = Customer::find($customer_id);
        $customer->markAsAgreed();
        // dd($customer);
        return redirect(route('customer.password.reset'));
    }

    public function checkoutFinal()
    {
        $today_open_time = SysConfig::getTodayOpenTime();
        $data = [
            'today_open_time' => $today_open_time,
        ];
        return view('pages.profile.receipt_final', $data);
    }

    public function checkoutSuccess()
    {
        $today_open_time = SysConfig::getTodayOpenTime();
        $data = [
            'today_open_time' => $today_open_time,
        ];
        return view('pages.profile.receipt_success', $data);
    }

    public function reloadedWithMessage(Request $request)
    {
        return redirect($request->url)->with('success', $request->message);
    }

    ## Additional Information
    public function myAdditionalInfo()
    {
        //dd(Auth::guard('customer')->user());
        $today_open_time = SysConfig::getTodayOpenTime();
        $customer_id = Auth::guard('customer')->user()->id;
        $customer = Customer::find($customer_id);
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

        $documents = CustomerDocument::where('customer_id',$customer_id)->where('type','!=','document')->whereNotNull('type')->get();

        $doc_data = [];
        $doc_data['label'] = 'IDENTIFICATION DOCUMENT';
        $doc_data['doc'] = [];
        foreach ($documents as $key => $document) {
            $ext = pathinfo(asset($document->file_path), PATHINFO_EXTENSION);

            if(Auth::guard('customer')->user()->id_type != null && Auth::guard('customer')->user()->id_type == 'nric') {
                if($document->type == 'nric'){
                    $doc_data['label'] = 'IDENTIFICATION DOCUMENT';
                    $doc_data['doc'][] = [
                        'id' => $document->id,
                        'ext' => $ext,
                        'file_name' => $document->file_name,
                        'full_path' => $document->full_path,
                    ];
                }
            }
            if(Auth::guard('customer')->user()->id_type != null && Auth::guard('customer')->user()->id_type == 'fin') {
                if($document->type == 'fin'){
                    $doc_data['label'] = 'IDENTIFICATION DOCUMENT';
                    $doc_data['doc'][] = [
                        'id' => $document->id,
                        'ext' => $ext,
                        'file_name' => $document->file_name,
                        'full_path' => $document->full_path,
                    ];
                }
            }
            if(Auth::guard('customer')->user()->id_type != null && Auth::guard('customer')->user()->id_type == 'passport') {
                if($document->type == 'passport'){
                    $doc_data['label'] = 'IDENTIFICATION DOCUMENT';
                    $doc_data['doc'][] = [
                        'id' => $document->id,
                        'ext' => $ext,
                        'file_name' => $document->file_name,
                        'full_path' => $document->full_path,
                    ];
                }
            }
        }

        $kyc_address = $this->profileRepository->getKycAddress($customer_id);

        $exist_kyc_address = 'N';
        $count_kyc_address = $this->profileRepository->getCountKycAddress($customer_id);
        if($count_kyc_address > 0){
            $exist_kyc_address = 'Y';
        }

        $addresses = DB::table('customer_addresses')
            ->where('customer_addresses.customer_id', '=', $customer_id)
            ->join('addresses', 'addresses.id', 'customer_addresses.address_id')
            ->where('addresses.type', '!=', 'kyc')
            ->select('addresses.*')
            ->orderBy('customer_addresses.address_id', 'desc')
            ->get();

        $address_list = [''=>'--- Select Address ---'];
        foreach ($addresses as $key => $address) {
            $address_name = $address->address_nickname;
            if($address->type == 'correspondence'){
                $address_name = 'Correspondence Address';
            }

            $address_list[$address->id] = $address_name;
        }

        $data = [
            'today_open_time' => $today_open_time,
            'customer_id' => $customer_id,
            'customer' => $customer,
            'countries' => $countries,
            'doc_data' => $doc_data,
            'kyc_address' => $kyc_address,
            'exist_kyc_address' => $exist_kyc_address,
            'address_list' => $address_list,
        ];
        return view('pages.profile.my_additional_info', $data);
    }

    public function myAdditionalInfoEdit()
    {
        $today_open_time = SysConfig::getTodayOpenTime();
        $customer_id = Auth::guard('customer')->user()->id;
        $customer = Customer::find($customer_id);
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

        $documents = CustomerDocument::where('customer_id',$customer_id)->where('type','!=','document')->whereNotNull('type')->get();

        $doc_data = [];
        foreach ($documents as $key => $document) {
            if(Auth::guard('customer')->user()->id_type != null && Auth::guard('customer')->user()->id_type == 'nric') {
                if($document->type == 'nric'){
                    $doc_data[] = [
                        'id' => $document->id,
                        'full_path' => $document->full_path,
                    ];
                }
            }
            if(Auth::guard('customer')->user()->id_type != null && Auth::guard('customer')->user()->id_type == 'fin') {
                if($document->type == 'fin'){
                    $doc_data[] = [
                        'id' => $document->id,
                        'full_path' => $document->full_path,
                    ];
                }
            }
            if(Auth::guard('customer')->user()->id_type != null && Auth::guard('customer')->user()->id_type == 'passport') {
                if($document->type == 'passport'){
                    $doc_data[] = [
                        'id' => $document->id,
                        'full_path' => $document->full_path,
                    ];
                }
            }
        }

        $nric_document_datas = $this->profileRepository->getCustomerDocumentData($customer_id,'nric');
        $fin_document_datas = $this->profileRepository->getCustomerDocumentData($customer_id,'fin');
        $passport_document_datas = $this->profileRepository->getCustomerDocumentData($customer_id,'passport');

        $kyc_address = $this->profileRepository->getKycAddress($customer_id);

        $exist_kyc_address = 'N';
        $count_kyc_address = $this->profileRepository->getCountKycAddress($customer_id);
        if($count_kyc_address > 0){
            $exist_kyc_address = 'Y';
        }

        $address_count = 0;
        $addresses = $this->profileRepository->getCutomerAddressForKyc($customer_id);
        // dd($addresses);
        if (!empty($addresses)) {
            $address_count = $addresses->count();
        }
        // dd($address_count);

        $data = [
            'today_open_time' => $today_open_time,
            'customer_id' => $customer_id,
            'customer' => $customer,
            'countries' => $countries,
            'doc_data' => $doc_data,
            'hide_nric_doc_ids' => $nric_document_datas['hide_customer_ids'],
            'hide_fin_doc_ids' => $fin_document_datas['hide_customer_ids'],
            'hide_passport_doc_ids' => $passport_document_datas['hide_customer_ids'],
            'nric_initialpreview' => $nric_document_datas['customer_initialpreview'],
            'nric_initialpreviewconfig' => $nric_document_datas['customer_initialpreviewconfig'],
            'fin_initialpreview' => $fin_document_datas['customer_initialpreview'],
            'fin_initialpreviewconfig' => $fin_document_datas['customer_initialpreviewconfig'],
            'passport_initialpreview' => $passport_document_datas['customer_initialpreview'],
            'passport_initialpreviewconfig' => $passport_document_datas['customer_initialpreviewconfig'],
            'kyc_address' => $kyc_address,
            'exist_kyc_address' => $exist_kyc_address,
            'addresses' => $addresses,
            'address_count' => $address_count,
        ];
        return view('pages.profile.my_additional_info_edit', $data);
    }

    public function updateAdditionalInfo(Request $request)
    {
        DB::beginTransaction();
        try {
            $customer_id = Auth::guard('customer')->user()->id;
            $customer = Customer::find($customer_id);
            $payload = $this->additionalInfoPackData($request);
            // dd($request->all());
            $result = $this->customerRepository->update($customer_id, $payload);

            if($request->address_id){
                $address = DB::table('addresses')->where('id',$request->address_id)->first();
                if($address && $address->type != 'kyc'){
                    $address_data = $this->kycAddressPackData($address);
                    if($customer){
                        $address_data['firstname'] = $customer->firstname;
                        $address_data['lastname'] = $customer->lastname;
                    }

                    $address_id = DB::table('addresses')->insertGetId($address_data);

                    DB::table('customer_addresses')->insertGetId(
                        ['customer_id' => $customer_id, 'address_id' => $address_id, 'is_primary' => 0]
                    );
                }
            }

            DB::commit();
            return redirect(route('my-additional-info'))->with('success', 'Your Additional Information has been updated.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e->getMessage());
            return redirect()->back()->withInput()->with(['error' => $e->getMessage()]);
        }
    }

    protected function additionalInfoPackData($request)
    {
        $payload['legal_name'] = $request->legal_name ?? null;
        $payload['date_of_birth'] = isset($request->date_of_birth)? date("Y-m-d", strtotime($request->date_of_birth)) : null;
        $payload['occupation'] = $request->occupation ?? null;
        $payload['citizenship_type'] = $request->citizenship_type ?? null;
        $payload['citizenship_one'] = $request->citizenship_one ?? null;
        $payload['citizenship_two'] = $request->citizenship_two ?? null;
        $payload['id_type'] = $request->id_type ?? null;
        $payload['nric'] = $request->nric ?? null;
        $payload['fin'] = $request->fin ?? null;
        $payload['passport'] = $request->passport ?? null;
        $payload['country_of_issue'] = $request->country_of_issue ?? null;
        $payload['passport_expiry_date'] = isset($request->passport_expiry_date)? date("Y-m-d", strtotime($request->passport_expiry_date)) : null;
        $payload['nric_document_ids'] = $request->hide_nric_doc_ids ?? null;
        $payload['fin_document_ids'] = $request->hide_fin_doc_ids ?? null;
        $payload['passport_document_ids'] = $request->hide_passport_doc_ids ?? null;

        return $payload;
    }

    public function customerDocumentUpload($customer_id, $type, Request $request)
    {
        try {
            // dd( $request->all() );
            if($type == 'document'){
                $identity_documents = $request->file('customer_document');
            }
            if($type == 'nric'){
                $identity_documents = $request->file('nric_document');
            }
            if($type == 'fin'){
                $identity_documents = $request->file('fin_document');
            }
            if($type == 'passport'){
                $identity_documents = $request->file('passport_document');
            }
            // dd($identity_documents);
            if (isset($identity_documents) && $identity_documents != null) {

                $p1 = [];
                $p2 = [];
                $customer_document_ids = [];
                $customer_document = $identity_documents[0];
                // dd($customer_document);

                if (isset($customer_document)) {

                    $file_path = Storage::put('customer/'.$customer_id, $customer_document);
                    $file_name = $customer_document->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $insert_customer_documents = [
                        'customer_id' => $customer_id,
                        'type' => $type,
                        'file_name' => $file_name,
                        'file_path' => $file_path,
                        'full_path' => $full_path,
                    ];

                    $customer_document_id = CustomerDocument::insertGetId($insert_customer_documents + NHelpers::created_updated_at());


                    $customer_document_ids[] = $customer_document_id;
                    if (!isset($customer_document_id)) {
                        echo '{}';
                        return;
                    } else {
                        $customer_document_obj = CustomerDocument::find($customer_document_id);
                        // $j = $i + 1;
                        $key = '<code to parse your document key>';
                        $url = '/manage/customers/'.$customer_document_id.'/document_delete';
                        $p1[] = $customer_document_obj->full_path; // sends the data
                        $p2[] = [
                            'caption' => $customer_document_obj->file_name,
                            // 'type' => $type, 'size' => '57071', 'width' => '263px','height' => '217px',
                            'url' => $url, 'key' => $customer_document_id, 'extra' => ['_token'=>csrf_token()]
                        ];
                    }
                }

                $data = [
                    'status'=>1,
                    'ids'=>$customer_document_ids,
                    'initialPreview' => $p1,
                    'initialPreviewConfig' => $p2,
                    'append' => true // whether to append these configurations to initialPreview.
                                     // if set to false it will overwrite initial preview
                                     // if set to true it will append to initial preview
                                     // if this propery not set or passed, it will default to true.
                ];

                return json_encode($data);
            }
        } catch (Exception $e) {
            return json_encode(array('status'=>-1,'message'=>$e->getMessage()));
        }
    }

    public function customerDocumentDelete(Request $request, $customer_document_id)
    {
        try {
            if ($customer_document_id) {
                $customer_document = CustomerDocument::where('id', $customer_document_id)->first();
                Storage::delete($customer_document->file_path);
                $customer_document->forceDelete();

                return response()->json(array('status'=>1,'message'=>'Identity Document Delete successfully!','customer_document_id'=>$customer_document_id));
            }
            return response()->json(array('status'=>-1,'message'=>'Identity Document Delete failed!','customer_document_id'=>$customer_document_id));
        } catch (Exception $e) {
            return response()->json(array('status'=>-1,'message'=>$e->getMessage()));
        }
    }

    public function myDownload($type)
    {
        if($type == 'auction_invoice'){
            $url = asset('ecommerce/pdf/profile/AuctionInvoiceExplainer2023.pdf');
            return response(file_get_contents($url), 200, [
                'Content-Disposition' => 'attachment; filename="hlzexplainerai010123.pdf"',
            ]);
        }
        if($type == 'marketplace_invoice'){
            $url = asset('ecommerce/pdf/profile/MarketplaceInvoiceExplainer2023.pdf');
            return response(file_get_contents($url), 200, [
                'Content-Disposition' => 'attachment; filename="hlzexplainermp010123.pdf"',
            ]);
        }
        if($type == 'settlement_payments'){
            $url = asset('ecommerce/pdf/profile/SettlementStatementExplainer2023.pdf');
            return response(file_get_contents($url), 200, [
                'Content-Disposition' => 'attachment; filename="hlzexplainerss010123.pdf"',
            ]);
        }
        if($type == 'sales_contract'){
            $url = asset('ecommerce/pdf/profile/SalesContractExplainer2023.pdf');
            return response(file_get_contents($url), 200, [
                'Content-Disposition' => 'attachment; filename="hlzexplainersc010123.pdf"',
            ]);
        }
    }

    public function getAddress(Request $request)
    {
        try {
            $customer_id = $request->customer_id;
            $customer = Customer::find($customer_id);
            $address_id = $request->address_id;

            $kyc_address = DB::table('addresses')->where('addresses.id', $address_id)
                    ->whereNull('addresses.deleted_at')
                    ->join('customer_addresses', 'customer_addresses.address_id', 'addresses.id')
                    ->where('customer_addresses.customer_id', $customer_id)
                    ->whereNull('customer_addresses.deleted_at')
                    ->select('addresses.*', 'customer_addresses.customer_id')
                    ->first();

            $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

            $returnHTML = view('pages.profile.partials.kyc_address_detail', [
                'kyc_address' => $kyc_address,
                'countries' => $countries,
                'customer' => $customer,
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Get Address Successfully.', 'html'=>$returnHTML));
        } catch (Exception $e) {
            \Log::info('getAddress Error : '.print_r($e->getMessage(), true));
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function addKycAddress(Request $request)
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $payload = $this->kycAddressPackData($request);
        try {

            $customer = Customer::find($customer_id);
            if($customer){
                $payload['firstname'] = $customer->firstname;
                $payload['lastname'] = $customer->lastname;
            }

            $address_id = DB::table('addresses')->insertGetId($payload);

            DB::table('customer_addresses')->insertGetId(
                ['customer_id' => $customer_id, 'address_id' => $address_id, 'is_primary' => 0]
            );

            return response()->json(array('status' => 'success','message'=>'Address has been created.'));
            // return redirect(route('my-additional-info'))->with('success', 'Address has been added.');

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return \Response::json(array('status'=>'failed','message'=>'Address is not created. Please try again later.'));
            // return redirect()->back()->withInput()->with(['error' => 'Address is not added. Please try again later.']);
        }
    }

    public function updateKycAddress(Request $request)
    {
        try {
            $address_id = $request->address_id;
            $customer_id = Auth::guard('customer')->user()->id;
            $customer = Customer::find($customer_id);

            $payload = $this->kycAddressPackData($request);
            if($customer){
                $payload['firstname'] = $customer->firstname;
                $payload['lastname'] = $customer->lastname;
            }

            $result = DB::table('addresses')->where('id', $address_id)->update($payload);

            return response()->json(array('status' => 'success','message'=>'Address has been updated.'));

            // return redirect(route('my-additional-info'))->with('success', 'Address has been updated.');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return \Response::json(array('status'=>'failed','message'=>'Address is not updated. Please try again later.'));
            // return redirect()->back()->withInput()->with(['error' => 'Address is not updated. Please try again later.']);
        }
    }

    public function deleteKycAaddress(Request $request)
    {
        $address_id = $request->address_id;
        $customer_id = Auth::guard('customer')->user()->id;

        try {
            DB::table('customer_addresses')->where('address_id', $address_id)->delete();
            DB::table('addresses')->where('id', '=', $address_id)->delete();
            return response()->json(array('status' => 'success','message'=>'Address has been deleted'));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    protected function kycAddressPackData($request)
    {
        if ($request->firstname) {
            $payload['firstname'] = $request->firstname;
        }

        if ($request->lastname) {
            $payload['lastname'] = $request->lastname;
        }

        if ($request->address) {
            $payload['address'] = $request->address;
        }

        if ($request->city) {
            $payload['city'] = $request->city;
        }

        if ($request->postalcode) {
            $payload['postalcode'] = $request->postalcode;
        }

        if ($request->country_id) {
            $payload['country_id'] = $request->country_id;
        }

        if ($request->state) {
            $payload['state'] = $request->state;
        }

        $payload['type'] = 'kyc';

        return $payload;
    }

}