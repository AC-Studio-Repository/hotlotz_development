<?php

namespace App\Modules\Customer\Http\Controllers;

use DB;
use PDF;
use Auth;
use View;
use Cookie;
use Response;
use App\User;
use App\Models\TimeZone;
use App\Helpers\NHelpers;
use Illuminate\Http\Request;
use App\Events\ItemHistoryEvent;
use App\Modules\Item\Models\Item;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Events\CustomerCreatedEvent;
use App\Events\CustomerUpdatedEvent;
use App\Modules\Xero\Models\XeroItem;
use App\Repositories\CountryRepository;
use Illuminate\Support\Facades\Storage;
use App\Modules\Item\Models\AuctionItem;
use App\Events\Item\SellerAgreementEvent;
use App\Modules\Category\Models\Category;
use App\Modules\Customer\Models\Customer;
use App\Modules\Item\Models\ItemLifecycle;
use App\Events\Xero\XeroAdhocInvoiceEvent;
use App\Modules\Customer\Models\CustomerNote;
use App\Modules\Customer\Models\CustomerType;
use App\Events\Item\DroppedOffItemReceivedEvent;
use App\Events\Xero\XeroPrivateSaleInvoiceEvent;
use App\Modules\Customer\Models\CustomerInvoice;
use App\Modules\Customer\Models\CustomerDocument;
use App\Modules\OrderSummary\Models\OrderSummary;
use App\Modules\Customer\Models\CustomerInterests;
use App\Modules\EmailTemplate\Models\EmailTemplate;
use App\Modules\Customer\Models\CustomerInvoiceItem;
use Konekt\AppShell\Http\Controllers\BaseController;
use App\Modules\Customer\Http\Requests\StoreCustomer;
use App\Modules\Customer\Http\Requests\UpdateCustomer;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Modules\Xero\Repositories\XeroControlRepository;
use App\Modules\Customer\Repositories\CustomerRepository;
use App\Modules\Customer\Http\Requests\AjaxCreateCustomer;
use App\Modules\OrderSummary\Http\Repositories\OrderSummaryRepository;
use App\Modules\Item\Models\ItemImage;
use App\Events\Client\SendKycCompanySellerEmailEvent;
use App\Events\Client\SendKycIndividualSellerEmailEvent;

class CustomerController extends BaseController
{
    protected $customerRepository;
    protected $countryRepository;
    protected $orderSummaryRepository;
    protected $itemRepository;

    public function __construct(
        CustomerRepository $customerRepository,
        OrderSummaryRepository $orderSummaryRepository,
        CountryRepository $countryRepository,
        ItemRepository $itemRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->orderSummaryRepository = $orderSummaryRepository;
        $this->countryRepository = $countryRepository;
        $this->itemRepository = $itemRepository;

    }

    public function index()
    {
        session()->forget('customer_tab');
        $customers = Customer::orderBy('id', 'DESC')->paginate(10);
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();
        $admin_users = DB::table('users')->orderBy('name')->pluck('name', 'id')->all();

        return view('customer::index', [
            'customers' => $customers,
            'countries' => $countries,
            'admin_users' => $admin_users,
        ]);
    }

    public function fetch_data(Request $request)
    {
        // dd($request->all());
        try {
            $per_page = isset($request->per_page)?(int)$request->per_page:10;

            $sort_by = isset($request->sort_by)?$request->sort_by:'customers.id';
            $sort_type = isset($request->sort_type)?$request->sort_type:'desc';

            $query = Customer::orderBy($sort_by, $sort_type)->select('customers.*');

            //Filter by Name/ReferenceNo
            if (isset($request->search_text)) {
                $filterString = NHelpers::getStringBetween($request->search_text, ' (', ')');
                if($filterString != ""){
                    $request->search_text = $filterString;
                    $query->where(function ($query2) use ($request) {
                        $query2->where('customers.ref_no', $request->search_text);
                    });
                }else{
                    $query->where(function ($query2) use ($request) {
                        $query2->where('customers.fullname', 'like', '%'.$request->search_text.'%');
                        $query2->orWhere('customers.ref_no', 'like', '%'.$request->search_text.'%');
                        $query2->orWhere('customers.email', 'like', '%'.$request->search_text.'%');
                        $query2->orWhere('customers.phone', 'like', '%'.$request->search_text.'%');
                        $query2->orWhere('customers.company_name', 'like', '%'.$request->search_text.'%');
                    });
                }

            }
            if (isset($request->country_id)) {
                $query->join('countries', function ($join) use ($request) {
                    $join->on('countries.id', '=', DB::raw('(SELECT countries.id FROM countries WHERE countries.id = customers.country_of_residence and countries.id ='.$request->country_id.' )'));
                });
            }
            if (isset($request->main_client_contact)) {
                $query->where('customers.main_client_contact', $request->main_client_contact);
            }
            if (isset($request->status)) {
                $query->where('customers.is_active', $request->status);
            }

            $customers = $query->paginate($per_page);

            $returnHTML = view('customer::_pagination', [
                'customers' => $customers,
            ])->render();

            return response()->json(array('status' => '1','message'=>'Filter and Sorting Successfully.', 'html'=>$returnHTML));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    public function create()
    {
        $customer = app(Customer::class);
        $salutations = NHelpers::getSalutations();
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();
        $states = [];
        $cities = [];
        $postal_codes = [];
        $sellers_commissions = ['none'=>'None', 'default'=>'Default'];
        $payment_types = ['cash'=>'Cash'];
        $buyer_premium_overrides = ['none'=>'None', 'default'=>'Default'];
        $hide_customer_ids = '';
        $categories = Category::where('parent_id', null)->where('name', '!=', 'Collaborations')->orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $category_interests = [];
        $ref_no = Customer::getCustomerRefNo();
        $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');

        $admin_users = DB::table('users')->orderBy('name')->pluck('name', 'id')->all();


        return view('customer::create', [
            'customer' => $customer,
            'salutations' => $salutations,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'postal_codes' => $postal_codes,
            'types'    => CustomerType::choices(),
            'timezones' => TimeZone::pluck('location'),
            'sellers_commissions' => $sellers_commissions,
            'payment_types' => $payment_types,
            'buyer_premium_overrides' => $buyer_premium_overrides,
            'categories' => $categories,
            'category_interests' => $category_interests,
            'hide_customer_ids' => $hide_customer_ids,
            'ref_no' => $ref_no,
            'country_codes' => $country_codes,
            'admin_users' => $admin_users,
            'form_type'=>'create',
            'is_admin_role'=>'yes',
            'customer_initialpreview'=>null,
            'customer_initialpreviewconfig'=>null,
        ]);
    }

    /**
     * @param StoreCustomer $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreCustomer $request)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());
            $payload = $request->payload($request->all());
            $ref_no_count = Customer::where('ref_no',$payload['ref_no'])->count();
            if($ref_no_count > 0){
                $payload['ref_no'] = Customer::getCustomerRefNo();
            }

            $email_count = Customer::where('email',$payload['email'])->count();
            if($email_count > 0){
                DB::rollback();
                flash()->error(__('Error: :msg', ['msg' => 'Customer create failed. This email has already been taken.']));
                return redirect()->back()->withInput();
            }

            $customer = $this->customerRepository->create($payload);

            if ($customer) {
                if (isset($request->category_interests)) {
                    $category_interestss = $request->category_interests;
                    foreach ($category_interestss as $key => $category_id) {
                        $cat_interest_data = [
                            'customer_id'=>$customer->id,
                            'what_we_sell_id'=>$category_id,
                        ];
                        CustomerInterests::create($cat_interest_data);
                    }
                }

                if ($request->hide_customer_ids != null && strlen($request->hide_customer_ids) > 0) {
                    $customer_document_arr = explode(",", $request->hide_customer_ids);
                    foreach ($customer_document_arr as $key => $customer_document_id) {
                        if (isset($customer_document_id) && $customer_document_id > 0) {
                            \Log::info('customer_id : '.print_r($customer->id, true));
                            \Log::info('customer_document_id : '.print_r($customer_document_id, true));

                            $customer_document = CustomerDocument::find($customer_document_id);
                            $customer_document->customer_id = $customer->id;
                            $customer_document->type = 'document';
                            $customer_document->save();

                            $new_path = 'customer/'.$customer->id;

                            $fileContent = Storage::get($customer_document->file_path);
                            $name = (string) \Str::uuid();
                            $path_parts = pathinfo($customer_document->file_path);
                            \Log::info('path_parts : '.print_r($path_parts, true));

                            $extension = $path_parts['extension'];
                            $new_file_path = $new_path.'/'.$name .'.'. $extension;
                            Storage::put($new_file_path, $fileContent);
                            \Log::info('new_file_path : '.print_r($new_file_path, true));

                            $new_full_path = Storage::url($new_file_path);
                            \Log::info('new_full_path : '.print_r($new_full_path, true));

                            Storage::delete($customer_document->file_path);

                            // $customer_document->file_name = $name .'.'. $extension;
                            $customer_document->file_path = $new_file_path;
                            $customer_document->full_path = $new_full_path;
                            $customer_document->save();
                        }
                    }
                }

                DB::commit();
                flash()->success(__(':name has been created', ['name' => $customer->fullname]));
                return redirect(route('customer.customers.show_customer', [$customer,'contact_details']));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Customer create failed']));
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    public function edit(Customer $customer)
    {
        return redirect(route('customer.customers.edit_customer', [$customer, 'contact_details' ]));

        // dd(session('customer_tab'));
        if (!session()->has('customer_tab')) {
            session(['customer_tab'=>'contact_details']);
        }

        $salutations = NHelpers::getSalutations();
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();
        $states = [];
        $cities = [];
        $postal_codes = [];
        $sellers_commissions = ['none'=>'None', 'default'=>'Default'];
        $payment_types = ['cash'=>'Cash'];
        $buyer_premium_overrides = ['none'=>'None', 'default'=>'Default'];

        $categories = Category::where('parent_id', null)->where('name', '!=', 'Collaborations')->orderBy('name', 'ASC')->pluck('name', 'id')->all();

        $category_interests = Customer::getCustomerInterests($customer->id);
        // dd($category_interests);

        $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');

        $customer_document_datas = Customer::getCustomerDocumentData($customer->id);
        $nric_document_datas = Customer::getCustomerDocumentData($customer->id,'nric');
        $fin_document_datas = Customer::getCustomerDocumentData($customer->id,'fin');
        $passport_document_datas = Customer::getCustomerDocumentData($customer->id,'passport');


        $xero_items = XeroItem::pluck('item_name', 'id')->all();
        $private_items = Item::where('lifecycle_id', '>', 0)->where('permission_to_sell', 'Y')->where('private_sale_type', null)
        ->whereHas('lifecycle', function ($query) {
            return $query->where('name', '=', 'Private Sale');
        })->selectRaw("id, concat(name, ' (', item_number, ')') as custom_item_name")->pluck('custom_item_name', 'id');

        $adhoc_invoices = $customer->invoices()->where('invoice_type', 'adhoc')->get();

        $admin_users = DB::table('users')->orderBy('name')->pluck('name', 'id')->all();

        $bank_countries = DB::table('countries')->where('id', '!=', 702)->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

        $user = Auth::user();
        $roles = $user->getRoleNames();
        $is_admin_role = 'no';
        foreach ($roles as $key => $role) {
            if($role === 'admin'){
                $is_admin_role = 'yes';
            }
        }

        $notes = $this->customerRepository->getCutomerNote($customer->id);
        $admin_id = Auth::user()->id;

        return view('customer::edit', [
            'customer' => $customer,
            'salutations' => $salutations,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'postal_codes' => $postal_codes,
            'types'     => CustomerType::choices(),
            'timezones' => TimeZone::pluck('location'),
            'sellers_commissions' => $sellers_commissions,
            'payment_types' => $payment_types,
            'buyer_premium_overrides' => $buyer_premium_overrides,
            'categories' => $categories,
            'category_interests' => $category_interests,
            'hide_customer_ids' => $customer_document_datas['hide_customer_ids'],
            'customer_initialpreview' => $customer_document_datas['customer_initialpreview'],
            'customer_initialpreviewconfig' => $customer_document_datas['customer_initialpreviewconfig'],
            'ref_no' => $customer->ref_no,
            'country_codes' => $country_codes,
            'xero_items' => $xero_items,
            'private_items' => $private_items,
            'adhoc_invoices' => $adhoc_invoices,
            'admin_users' => $admin_users,
            'bank_countries' => $bank_countries,
            'is_admin_role' => $is_admin_role,
            'form_type'=>'edit',
            'hide_nric_doc_ids' => $nric_document_datas['hide_customer_ids'],
            'hide_fin_doc_ids' => $fin_document_datas['hide_customer_ids'],
            'hide_passport_doc_ids' => $passport_document_datas['hide_customer_ids'],
            'nric_initialpreview' => $nric_document_datas['customer_initialpreview'],
            'nric_initialpreviewconfig' => $nric_document_datas['customer_initialpreviewconfig'],
            'fin_initialpreview' => $fin_document_datas['customer_initialpreview'],
            'fin_initialpreviewconfig' => $fin_document_datas['customer_initialpreviewconfig'],
            'passport_initialpreview' => $passport_document_datas['customer_initialpreview'],
            'passport_initialpreviewconfig' => $passport_document_datas['customer_initialpreviewconfig'],
            'notes' => $notes,
            'admin_id' => $admin_id,
        ]);
    }

    public function editCustomer(Customer $customer, $tab_name)
    {
        $salutations = NHelpers::getSalutations();
        $sellers_commissions = ['none'=>'None', 'default'=>'Default'];
        $payment_types = ['cash'=>'Cash'];
        $buyer_premium_overrides = ['none'=>'None', 'default'=>'Default'];

        $country_codes = DB::table('country_codes')->orderBy('order_by_status', 'desc')->orderBy('dialling_code')->pluck('dialling_code', 'dialling_code');

        $xero_items = XeroItem::pluck('item_name', 'id')->all();
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

        $user = Auth::user();
        $roles = $user->getRoleNames();
        $is_admin_role = 'no';
        foreach ($roles as $key => $role) {
            if($role === 'admin'){
                $is_admin_role = 'yes';
            }
        }
        $admin_id = Auth::user()->id;

        $data = [
            'customer' => $customer,
            'tab_name' => $tab_name,
            'salutations' => $salutations,
            'states' => [],
            'cities' => [],
            'postal_codes' => [],
            'types'     => CustomerType::choices(),
            // 'timezones' => TimeZone::pluck('location'),
            'sellers_commissions' => $sellers_commissions,
            'payment_types' => $payment_types,
            // 'buyer_premium_overrides' => $buyer_premium_overrides,
            'ref_no' => $customer->ref_no,
            'country_codes' => $country_codes,
            'xero_items' => $xero_items,
            // 'bank_countries' => $bank_countries,
            'form_type'=>'edit',
            'is_admin_role' => $is_admin_role,
            'admin_id' => $admin_id,
            'countries' => $countries,
        ];


        if($tab_name == 'contact_details') {
            $admin_users = DB::table('users')->orderBy('name')->pluck('name', 'id')->all();

            $data['admin_users'] = $admin_users;
        }
        if($tab_name == 'seller_details') {
            $no_permission_items = Item::where('customer_id', $customer->id)->where('is_valuation_approved', 'Y')->where('is_fee_structure_approved', 'Y')->where('permission_to_sell', '!=', 'Y')->where('is_hotlotz_own_stock', '!=', 'Y')->count();

            $data['no_permission_items'] = $no_permission_items;
        }
        if($tab_name == 'buyer_details') {
            // $data['notes'] = $notes;
        }
        if($tab_name == 'marketing') {
            $categories = Category::where('parent_id', null)->where('name', '!=', 'Collaborations')->orderBy('name', 'ASC')->pluck('name', 'id')->all();
            $category_interests = Customer::getCustomerInterests($customer->id);

            $data['categories'] = $categories;
            $data['category_interests'] = $category_interests;
        }
        if($tab_name == 'documents') {
            $customer_documents = CustomerDocument::where('customer_id', $customer->id)->get();
            $customer_document_datas = Customer::getCustomerDocumentData($customer->id);

            $data['customer_documents'] = $customer_documents;
            $data['hide_customer_ids'] = $customer_document_datas['hide_customer_ids'];
            $data['customer_initialpreview'] = $customer_document_datas['customer_initialpreview'];
            $data['customer_initialpreviewconfig'] = $customer_document_datas['customer_initialpreviewconfig'];
        }
        if($tab_name == 'adhoc_invoice') {
            $adhoc_invoices = $customer->invoices()->where('invoice_type', 'adhoc')->get();
            $data['adhoc_invoices'] = $adhoc_invoices;
        }
        if($tab_name == 'private_invoice') {
            $private_invoices = $customer->invoices()->where('invoice_type', 'private')->get();
            $data['private_invoices'] = $private_invoices;
        }
        if($tab_name == 'payments') {
            // $stripeCountries = $this->countryRepository->getAllCountriesForStripe();

            $bank_countries = DB::table('countries')->where('id', '!=', 702)->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

            // $data['stripeCountries'] = $stripeCountries;
            $data['bank_countries'] = $bank_countries;
        }
        if($tab_name == 'notes'){
            $notes = $this->customerRepository->getCutomerNote($customer->id);

            $data['notes'] = $notes;
        }
        if($tab_name == 'kyc') {
            $nric_document_datas = Customer::getCustomerDocumentData($customer->id,'nric');
            $fin_document_datas = Customer::getCustomerDocumentData($customer->id,'fin');
            $passport_document_datas = Customer::getCustomerDocumentData($customer->id,'passport');

            $data['hide_nric_doc_ids'] = $nric_document_datas['hide_customer_ids'];
            $data['hide_fin_doc_ids'] = $fin_document_datas['hide_customer_ids'];
            $data['hide_passport_doc_ids'] = $passport_document_datas['hide_customer_ids'];
            $data['nric_initialpreview'] = $nric_document_datas['customer_initialpreview'];
            $data['nric_initialpreviewconfig'] = $nric_document_datas['customer_initialpreviewconfig'];
            $data['fin_initialpreview'] = $fin_document_datas['customer_initialpreview'];
            $data['fin_initialpreviewconfig'] = $fin_document_datas['customer_initialpreviewconfig'];
            $data['passport_initialpreview'] = $passport_document_datas['customer_initialpreview'];
            $data['passport_initialpreviewconfig'] = $passport_document_datas['customer_initialpreviewconfig'];
        }
        // dd($data);
        return view('customer::edit', $data);
    }

    /**
     * @param Customer         $customer
     * @param UpdateCustomer $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Customer $customer, UpdateCustomer $request)
    {
        // dd($request->all());
        try {
            if($request->tab_name != 'documents' && $request->tab_name != 'adhoc_invoice') {
                $data = $request->payload($request->all());
                $result = $this->customerRepository->update($customer->id, $data['payload']);


                if ($data['tab_name'] == 'marketing' && isset($request->category_interests)) {
                    CustomerInterests::where('customer_id', $customer->id)->delete();
                    $category_interestss = $request->category_interests;
                    foreach ($category_interestss as $key => $category_id) {
                        $cat_interest_data = [
                            'customer_id'=>$customer->id,
                            'what_we_sell_id'=>$category_id,
                        ];
                        CustomerInterests::create($cat_interest_data);
                    }
                }
            }

            flash()->success(__(':name has been updated', ['name' => $customer->fullname]));
            return redirect(route('customer.customers.show_customer', [$customer, $request->tab_name]));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }


    public function show(Customer $customer)
    {
        return redirect(route('customer.customers.show_customer', [$customer, 'contact_details' ]));

        if (!session()->has('customer_tab')) {
            session(['customer_tab'=>'contact_details']);
        }

        $salutations = NHelpers::getSalutations();
        $states = [];
        $cities = [];
        $postal_codes = [];
        $sellers_commissions = ['none'=>'None', 'default'=>'Default'];
        $payment_types = ['cash'=>'Cash'];
        $buyer_premium_overrides = ['none'=>'None', 'default'=>'Default'];

        $categories = Category::where('parent_id', null)->where('name', '!=', 'Collaborations')->orderBy('name', 'ASC')->pluck('name', 'id')->all();
        // dd($categories);

        $category_interests = Customer::getCustomerInterests($customer->id);
        // dd($category_interests);

        $customer_documents = CustomerDocument::where('customer_id', $customer->id)->get();
        // dd($customer_documents);

        $xero_items = DB::table('xero_items')->pluck('item_name', 'id')->all();
        $private_items = Item::where('lifecycle_id', '>', 0)->where('permission_to_sell', 'Y')->where('private_sale_type', null)
        ->whereHas('lifecycle', function ($query) {
            return $query->where('name', '=', 'Private Sale');
        })->selectRaw("id, concat(name, ' (', item_number, ')') as custom_item_name")->pluck('custom_item_name', 'id');

        $no_permission_items = Item::where('customer_id', $customer->id)->where('permission_to_sell', '!=', 'Y')->where('is_hotlotz_own_stock', '!=', 'Y')->count();

        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

        $admin_users = DB::table('users')->orderBy('name')->pluck('name', 'id')->all();

        $addresses = $this->customerRepository->getCutomerAddress($customer->id);

        $exist_correspondence_address = 'N';
        $count_correspondence_address = $this->customerRepository->getCountCorrespondenceAddress($customer->id);
        if($count_correspondence_address > 0){
            $exist_correspondence_address = 'Y';
        }

        $stripeCountries = $this->countryRepository->getAllCountriesForStripe();

        $bank_countries = DB::table('countries')->where('id', '!=', 702)->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

        $notes = $this->customerRepository->getCutomerNote($customer->id);
        $customer_note = null;
        $admin_id = Auth::user()->id;


        $documents = CustomerDocument::where('customer_id',$customer->id)->where('type','!=','document')->whereNotNull('type')->get();

        $doc_data = [];
        $doc_data['label'] = 'Identification Document';
        $doc_data['doc'] = [];
        foreach ($documents as $key => $document) {
            $ext = pathinfo(asset($document->file_path), PATHINFO_EXTENSION);

            if($customer->id_type != null && $customer->id_type == 'nric') {
                if($document->type == 'nric'){
                    $doc_data['label'] = 'Identification Document';
                    $doc_data['doc'][] = [
                        'id' => $document->id,
                        'ext' => $ext,
                        'file_name' => $document->file_name,
                        'full_path' => $document->full_path,
                    ];
                }
            }
            if($customer->id_type != null && $customer->id_type == 'fin') {
                if($document->type == 'fin'){
                    $doc_data['label'] = 'Identification Document';
                    $doc_data['doc'][] = [
                        'id' => $document->id,
                        'ext' => $ext,
                        'file_name' => $document->file_name,
                        'full_path' => $document->full_path,
                    ];
                }
            }
            if($customer->id_type != null && $customer->id_type == 'passport') {
                if($document->type == 'passport'){
                    $doc_data['label'] = 'Identification Document';
                    $doc_data['doc'][] = [
                        'id' => $document->id,
                        'ext' => $ext,
                        'file_name' => $document->file_name,
                        'full_path' => $document->full_path,
                    ];
                }
            }
        }

        $sale_item_statuses = [
            'all' => 'All',
            Item::_SWU_ => Item::_SWU_,
            Item::_PENDING_ => Item::_PENDING_,
            Item::_DECLINED_ => Item::_DECLINED_,
            Item::_PENDING_IN_AUCTION_ => Item::_PENDING_IN_AUCTION_,
            Item::_IN_AUCTION_ => Item::_IN_AUCTION_,
            Item::_IN_MARKETPLACE_ => Item::_IN_MARKETPLACE_,
            Item::_SOLD_ => Item::_SOLD_,
            Item::_UNSOLD_ => Item::_UNSOLD_,
            Item::_PAID_ => Item::_PAID_,
            Item::_SETTLED_ => Item::_SETTLED_,
            Item::_WITHDRAWN_ => Item::_WITHDRAWN_,
            Item::_ITEM_RETURNED_ => Item::_ITEM_RETURNED_,
        ];

        $sold_item_statuses = [
            'all' => 'All',
            Item::_SOLD_ => Item::_SOLD_,
            Item::_PAID_ => Item::_PAID_,
            Item::_SETTLED_ => Item::_SETTLED_,
            Item::_ITEM_RETURNED_ => Item::_ITEM_RETURNED_,
        ];

        return view('customer::show.show', [
            'customer' => $customer,
            'salutations' => $salutations,
            'states' => $states,
            'cities' => $cities,
            'postal_codes' => $postal_codes,
            'types' => CustomerType::choices(),
            'sellers_commissions' => $sellers_commissions,
            'payment_types' => $payment_types,
            'buyer_premium_overrides' => $buyer_premium_overrides,
            'categories' => $categories,
            'category_interests' => $category_interests,
            'customer_documents' => $customer_documents,
            'xero_items' => $xero_items,
            'private_items' => $private_items,
            // 'sell_item_selected_all' => 'N',
            'no_permission_items' => $no_permission_items,
            'countries' => $countries,
            'admin_users' => $admin_users,
            'addresses' => $addresses,
            'stripeCountries' => $stripeCountries,
            'bank_account' => $customer->customer_bank_account,
            'bank_countries' => $bank_countries,
            'exist_correspondence_address' => $exist_correspondence_address,
            'notes' => $notes,
            'customer_note' => $customer_note,
            'admin_id' => $admin_id,
            'doc_data' => $doc_data,
            'sale_item_statuses' => $sale_item_statuses,
            'sold_item_statuses' => $sold_item_statuses,
        ]);
    }

    public function showCustomer(Customer $customer, $tab_name)
    {
        $salutations = NHelpers::getSalutations();
        $sellers_commissions = ['none'=>'None', 'default'=>'Default'];
        $payment_types = ['cash'=>'Cash'];
        $buyer_premium_overrides = ['none'=>'None', 'default'=>'Default'];
        $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

        $data = [
            'customer' => $customer,
            'tab_name' => $tab_name,
            'salutations' => $salutations,
            'states' => [],
            'cities' => [],
            'postal_codes' => [],
            'types' => CustomerType::choices(),
            'sellers_commissions' => $sellers_commissions,
            'payment_types' => $payment_types,
            'sell_item_selected_all' => 'N',
            'countries' => $countries,
        ];


        if($tab_name == 'contact_details') {

            $admin_users = DB::table('users')->orderBy('name')->pluck('name', 'id')->all();

            $addresses = $this->customerRepository->getCutomerAddress($customer->id);

            $exist_correspondence_address = 'N';
            $count_correspondence_address = $this->customerRepository->getCountCorrespondenceAddress($customer->id);
            if($count_correspondence_address > 0){
                $exist_correspondence_address = 'Y';
            }

            $exist_kyc_address = 'N';
            $count_kyc_address = $this->customerRepository->getCountKycAddress($customer->id);
            if($count_kyc_address > 0){
                $exist_kyc_address = 'Y';
            }

            $address_list = [''=>'--- Select Address ---'];
            foreach ($addresses as $key => $value) {
                $address_name = $value['address_nickname'];
                if($value['type'] == 'correspondence'){
                    $address_name = 'Correspondence Address';
                }

                $address_list[$value['address_id']] = $address_name;
            }
            // dd($address_list);

            $data['countries'] = $countries;
            $data['admin_users'] = $admin_users;
            $data['addresses'] = $addresses;
            $data['exist_correspondence_address'] = $exist_correspondence_address;
            $data['exist_kyc_address'] = $exist_kyc_address;
            $data['address_list'] = $address_list;
        }
        if($tab_name == 'seller_details') {
            $no_permission_items = Item::where('customer_id', $customer->id)->where('is_valuation_approved', 'Y')->where('is_fee_structure_approved', 'Y')->where('permission_to_sell', '!=', 'Y')->where('is_hotlotz_own_stock', '!=', 'Y')->count();

            $sale_item_statuses = [
                'all' => 'All',
                Item::_SWU_ => Item::_SWU_,
                Item::_PENDING_ => Item::_PENDING_,
                Item::_DECLINED_ => Item::_DECLINED_,
                Item::_PENDING_IN_AUCTION_ => Item::_PENDING_IN_AUCTION_,
                Item::_IN_AUCTION_ => Item::_IN_AUCTION_,
                Item::_IN_MARKETPLACE_ => Item::_IN_MARKETPLACE_,
                Item::_SOLD_ => Item::_SOLD_,
                Item::_UNSOLD_ => Item::_UNSOLD_,
                Item::_PAID_ => Item::_PAID_,
                Item::_SETTLED_ => Item::_SETTLED_,
                Item::_WITHDRAWN_ => Item::_WITHDRAWN_,
                Item::_ITEM_RETURNED_ => Item::_ITEM_RETURNED_,
            ];

            $data['no_permission_items'] = $no_permission_items;
            $data['sale_item_statuses'] = $sale_item_statuses;
        }
        if($tab_name == 'buyer_details') {
            $sold_item_statuses = [
                'all' => 'All',
                Item::_SOLD_ => Item::_SOLD_,
                Item::_PAID_ => Item::_PAID_,
                Item::_SETTLED_ => Item::_SETTLED_,
                Item::_ITEM_RETURNED_ => Item::_ITEM_RETURNED_,
            ];
            $data['sold_item_statuses'] = $sold_item_statuses;
        }
        if($tab_name == 'marketing') {

            $categories = Category::where('parent_id', null)->where('name', '!=', 'Collaborations')->orderBy('name', 'ASC')->pluck('name', 'id')->all();
            $category_interests = Customer::getCustomerInterests($customer->id);

            $data['categories'] = $categories;
            $data['category_interests'] = $category_interests;
        }
        if($tab_name == 'documents') {
            $customer_documents = CustomerDocument::where('customer_id', $customer->id)->get();

            $data['customer_documents'] = $customer_documents;
        }
        if($tab_name == 'adhoc_invoice') {
            $adhoc_invoices = $customer->invoices()->where('invoice_type', 'adhoc')->get();
            $xero_items = DB::table('xero_items')->pluck('item_name', 'id')->all();


            $data['adhoc_invoices'] = $adhoc_invoices;
            $data['xero_items'] = $xero_items;
        }
        if($tab_name == 'private_invoice') {
            $private_invoices = $customer->invoices()->where('invoice_type', 'private')->get();
            $private_items = Item::where('lifecycle_id', '>', 0)->where('permission_to_sell', 'Y')->where('private_sale_type', null)
                    ->whereHas('lifecycle', function ($query) {
                        return $query->where('name', '=', 'Private Sale');
                    })->selectRaw("id, concat(name, ' (', item_number, ')') as custom_item_name")->pluck('custom_item_name', 'id');

            $data['private_invoices'] = $private_invoices;
            $data['private_items'] = $private_items;
        }
        if($tab_name == 'payments') {
            $stripeCountries = $this->countryRepository->getAllCountriesForStripe();

            $bank_countries = DB::table('countries')->where('id', '!=', 702)->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

            $data['stripeCountries'] = $stripeCountries;
            $data['bank_countries'] = $bank_countries;
        }
        if($tab_name == 'notes') {
            $notes = $this->customerRepository->getCutomerNote($customer->id);
            $customer_note = null;
            $admin_id = Auth::user()->id;

            $data['notes'] = $notes;
            $data['customer_note'] = $customer_note;
            $data['admin_id'] = $admin_id;
        }
        if($tab_name == 'kyc') {
            $documents = CustomerDocument::where('customer_id',$customer->id)->where('type','!=','document')->whereNotNull('type')->get();

            $doc_data = [];
            $doc_data['label'] = 'Identification Document';
            $doc_data['doc'] = [];
            foreach ($documents as $key => $document) {
                $ext = pathinfo(asset($document->file_path), PATHINFO_EXTENSION);

                if($customer->id_type != null && $customer->id_type == 'nric') {
                    if($document->type == 'nric'){
                        $doc_data['label'] = 'Identification Document';
                        $doc_data['doc'][] = [
                            'id' => $document->id,
                            'ext' => $ext,
                            'file_name' => $document->file_name,
                            'full_path' => $document->full_path,
                        ];
                    }
                }
                if($customer->id_type != null && $customer->id_type == 'fin') {
                    if($document->type == 'fin'){
                        $doc_data['label'] = 'Identification Document';
                        $doc_data['doc'][] = [
                            'id' => $document->id,
                            'ext' => $ext,
                            'file_name' => $document->file_name,
                            'full_path' => $document->full_path,
                        ];
                    }
                }
                if($customer->id_type != null && $customer->id_type == 'passport') {
                    if($document->type == 'passport'){
                        $doc_data['label'] = 'Identification Document';
                        $doc_data['doc'][] = [
                            'id' => $document->id,
                            'ext' => $ext,
                            'file_name' => $document->file_name,
                            'full_path' => $document->full_path,
                        ];
                    }
                }
            }

            $approvers = User::pluck('name', 'id')->all();

            $kyc_address = $this->customerRepository->getKycAddress($customer->id);

            $data['doc_data'] = $doc_data;
            $data['approvers'] = $approvers;
            $data['kyc_address'] = $kyc_address;
        }
        return view('customer::show.show', $data);
    }

    public function sellItemPaginate($customer_id, Request $request)
    {
        // dd($request->all());
        try {

            $sell_items_count = Item::where('customer_id', $customer_id)->count();

            $per_page = 10;

            if(isset($request->per_page)){
                $per_page = ($request->per_page == 'all') ? $sell_items_count : (int)$request->per_page;
            }

            $sell_items = Item::where('customer_id', $customer_id);
            if(isset($request->filter_seller_status) && $request->filter_seller_status != 'all'){
                $sell_items = $sell_items->where('status', $request->filter_seller_status);
            }

            $sell_items = $sell_items->orderBy('registration_date','desc')->paginate($per_page);

            $sell_items->withPath(route('customer.customers.sell_item',[$customer_id]));

            $returnHTML = view('customer::show.sell_items', [
                'sell_items' => $sell_items,
                // 'sell_item_selected_all' => $request->sell_item_selected_all,
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Get Sell Items Successfully', 'html'=>$returnHTML));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function purchasedItemPaginate($customer_id, Request $request)
    {
        // dd($customer_id);
        try {
            $per_page = isset($request->per_page)?(int)$request->per_page:10;

            $purchased_items = Item::where('buyer_id', $customer_id);
            if(isset($request->filter_buyer_status) && $request->filter_buyer_status != 'all'){
                $purchased_items = $purchased_items->where('status', $request->filter_buyer_status);
            }
            // dd($purchased_items->get());
            $purchased_items = $purchased_items->orderBy('sold_date','desc')->paginate($per_page);
            $purchased_items->withPath(route('customer.customers.purchased_item',[$customer_id]));
            // dd($purchased_items->links());

            $returnHTML = view('customer::show.purchased_items', [
                'purchased_items' => $purchased_items,
            ])->render();

            return response()->json(array('status' => 'success','message'=>'Get Purchased Items Successfully', 'html'=>$returnHTML));
        } catch (Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    /**
     * Delete a customer
     *
     * @param Customer $customer
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Customer $customer)
    {
        // dd($customer);
        try {
            //Check any usage in Items table
            $check_customer_in_item = Item::where('customer_id', $customer->id)->count();
            if ($check_customer_in_item > 0) {
                flash()->info(__('Item used this Customer :name. Can\'t be deleted!!', ['name' => $customer->fullname]));
            } else {
                $customer->delete();

                flash()->success(__('Customer :name has been deleted', ['name' => $customer->fullname]));
                return response()->json([ 'status'=>'success', 'message' => 'Customer '.$customer->fullname.' has been deleted']);
            }
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }

    public function getSelect2CustomerData()
    {
        // $customers = Customer::getSelect2CustomerData(request()->auction_id);
        $customers = Customer::getSelect2CustomerData(request()->all(), request()->auction_id);
        return $customers;
    }

    public function getSelect2CustomerDataById($customer_id)
    {
        $customer = Customer::where('id',$customer_id)->select('id', 'fullname', 'ref_no', 'firstname', 'lastname')->first();

        $seller = [];
        if($customer){
            $seller = [
                'id' => $customer->id,
                'text' => $customer->ref_no.'_'.$customer->select2_fullname,
            ];
        }

        return $seller;
    }

    public function checkUniqueCustomerEmail(Request $request)
    {
        try {
            $count = Customer::where('email', $request->email)->count();
            return \Response::json(array('status'=>'success','message'=>'Check Customer Email Uniqe successfully!','count'=>$count));
        } catch (\Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function ajaxCreate(AjaxCreateCustomer $request)
    {
        try {
            $customer = Customer::create($request->payload($request->all()));

            return \Response::json(array('status'=>'success','message'=>'Customer added successfully!','customer_id'=>$customer->id));
        } catch (\Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function getCustomerById($customer_id)
    {
        try {
            $customer = Customer::find($customer_id);

            return \Response::json(array('status'=>'1','customer'=>$customer));
        } catch (\Exception $e) {
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }


    public function customerDocumentUploadOld($id, Request $request)
    {
        // ini_set('memory_limit', '2048M');
        try {
            // dd( $request->all() );
            if ($customer_documents = $request->file('customer_document')) {
                // dd($customer_documents);

                $p1 = [];
                $p2 = [];
                $customer_documents_ids = [];

                $count_customer_documents = count($customer_documents);

                // dd(public_path('/customer_documents/'));
                for ($i=0; $i < $count_customer_documents; $i++) {
                    $customer_document = $customer_documents[$i];

                    if (isset($customer_document)) {
                        $customer_id = null;
                        if ($id != '0') {
                            $customer_id = $id;

                            $file_path = Storage::put('customer/'.$customer_id, $customer_document);
                            $file_name = $customer_document->getClientOriginalName();
                            $full_path = Storage::url($file_path);

                            $insert_customer_documents = [
                                'customer_id' => $customer_id,
                                'type' => 'document',
                                'file_name' => $file_name,
                                'file_path' => $file_path,
                                'full_path' => $full_path,
                            ];
                        } else {
                            $foldername = \Str::random('10');
                            $file_path = Storage::put('customer/'.$foldername, $customer_document);
                            $file_name = $customer_document->getClientOriginalName();
                            $full_path = Storage::url($file_path);

                            $insert_customer_documents = [
                                'customer_id' => $customer_id,
                                'type' => 'document',
                                'file_name' => $file_name,
                                'file_path' => $file_path,
                                'full_path' => $full_path,
                            ];
                        }
                        $customer_document_id = CustomerDocument::insertGetId($insert_customer_documents + NHelpers::created_updated_at());


                        $customer_documents_ids[] = $customer_document_id;
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
                }

                $data = [
                    'status'=>1,
                    'ids'=>$customer_documents_ids,
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

    public function customerDocumentUpload($id, $type, Request $request)
    {
        // ini_set('memory_limit', '2048M');
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
                    $customer_id = null;
                    if ($id != '0') {
                        $customer_id = $id;

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
                    } else {
                        $foldername = \Str::random('10');
                        $file_path = Storage::put('customer/'.$foldername, $customer_document);
                        $file_name = $customer_document->getClientOriginalName();
                        $full_path = Storage::url($file_path);

                        $insert_customer_documents = [
                            'customer_id' => $customer_id,
                            'type' => $type,
                            'file_name' => $file_name,
                            'file_path' => $file_path,
                            'full_path' => $full_path,
                        ];
                    }
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
        // dd($customer_document_id);
        try {
            if ($customer_document_id) {
                $customer_document = CustomerDocument::where('id', $customer_document_id)->first();
                Storage::delete($customer_document->file_path);
                $customer_document->forceDelete();

                return response()->json(array('status'=>1,'message'=>'Document Delete successfully!','customer_document_id'=>$customer_document_id));
            }
            return response()->json(array('status'=>-1,'message'=>'Document Delete failed!','customer_document_id'=>$customer_document_id));
        } catch (Exception $e) {
            return response()->json(array('status'=>-1,'message'=>$e->getMessage()));
        }
    }

    public function createNewInvoice($customer_id, Request $request)
    {
        \Log::info('Start - createNewAdhocInvoice');
        DB::beginTransaction();
        try {
            if ($customer_id) {
                \Log::info('customer_id : '.$customer_id);

                if (isset($request->xero_item_id) && count($request->xero_item_id) > 0) {
                    $xero_data = [];
                    $xero_data['seller_id'] = $customer_id;
                    $xero_data['items'] = [];
                    $xero_data['order_id'] = null;

                    if ($request->order_id) {
                        $xero_data['order_id'] = $request->order_id;
                    }

                    $cust_invoice_data = [];
                    $count = count($request->xero_item_id);

                    for ($i=0; $i < $count; $i++) {
                        $xero_data['items'][] = [
                            'item_id' => $request->xero_item_id[$i],
                            'price' => $request->price[$i],
                            'notes' => $request->notes[$i],
                        ];
                    }

                    if ($request->order_id) {
                        $this->orderSummaryRepository->update($request->order_id, ['status' => OrderSummary::PENDING]);
                    }

                    ## get xero_invoice_id from XERO API event
                    if (count($xero_data['items']) > 0) {
                        \Log::info('xero_data : '.print_r($xero_data, true));
                        event(new XeroAdhocInvoiceEvent($xero_data));
                    }
                }

                DB::commit();
                return response()->json(array('status'=>1,'message'=>'New Invoice Create successfully!','invoice_id'=> 1));
            }

            return response()->json(array('status'=>-1,'message'=>'New Invoice Create failed!'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(array('status'=>-1,'message'=>$e->getMessage()));
        }
        \Log::info('End - createNewAdhocInvoice');
    }

    public function createNewInvoicePrivate($customer_id, Request $request)
    {
        \Log::info('Start - createNewInvoicePrivate');
        DB::beginTransaction();
        try {
            if ($customer_id) {
                \Log::info('customer_id : '.$customer_id);

                if (isset($request->item_id) && count($request->item_id) > 0) {
                    $xero_data = [];
                    $xero_data['seller_id'] = $customer_id;
                    $xero_data['items'] = [];

                    $cust_invoice_data = [];
                    $count = count($request->item_id);

                    for ($i=0; $i < $count; $i++) {
                        $sold_price_inclusive_gst = $request->price[$i];
                        $sold_price_exclusive_gst = $request->price[$i] / 1.08;

                        $payload = [
                            'status'=>Item::_SOLD_,
                            'lifecycle_status'=>Item::_PRIVATE_SALE_,
                            'private_sale_type' => 'privatesale',
                            'private_sale_auction_id' => null,
                            'private_sale_price' => $request->price[$i],
                            'private_sale_buyer_premium' => $request->buyer_premiun[$i],
                            'private_sale_date' => date('Y-m-d H:i:s'),
                            'sold_price' => $request->price[$i],
                            'sold_price_inclusive_gst' => $sold_price_inclusive_gst,
                            'sold_price_exclusive_gst' => $sold_price_exclusive_gst,
                            'sold_date' => date('Y-m-d H:i:s'),
                            'buyer_id' => $customer_id,
                            'tag' => 'in_storage',
                        ];
                        $result = $this->itemRepository->update($request->item_id[$i], $payload, true, 'PrivateSale');

                        //for Item History
                        $item = Item::find($request->item_id[$i]);
                        $item_history = [
                            'item_id' => $request->item_id[$i],
                            'customer_id' => $item->customer_id ?? null,
                            'auction_id' => null,
                            'item_lifecycle_id' => null,
                            'price' => null,
                            'type' => 'privatesale',
                            'status' => Item::_PRIVATE_SALE_,
                            'entered_date' => date('Y-m-d H:i:s'),
                        ];
                        \Log::info('call ItemHistoryEvent - PrivateSale');
                        event( new ItemHistoryEvent($item_history) );

                        $xero_data['items'][] = [
                            'item_id' => $request->item_id[$i],
                            'price' => $request->price[$i],
                            'buyer_premiun' => $request->buyer_premiun[$i],
                            'buyer_id' => $customer_id,
                            'sold_price_inclusive_gst' => $request->price[$i],
                            'sold_price_exclusive_gst' => $request->price[$i] / 1.08,
                            'type' => 'privatesale'
                        ];
                    }

                    ## get xero_invoice_id from XERO API event
                    if (count($xero_data['items']) > 0) {
                        \Log::info('xero_data : '.print_r($xero_data, true));
                        event(new XeroPrivateSaleInvoiceEvent($xero_data, true));
                    }
                }

                DB::commit();
                flash()->success(__('New Invoice Create successfully!'));

                return response()->json(array('status'=>1,'message'=>'New Invoice Create successfully!','invoice_id'=> 1));
            }
            flash()->error(__('Error: :msg', ['msg' => 'New Invoice Create failed!']));
            return response()->json(array('status'=>-1,'message'=>'New Invoice Create failed!'));
        } catch (Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return response()->json(array('status'=>-1,'message'=>$e->getMessage()));
        }
        \Log::info('End - createNewInvoicePrivate');
    }

    public function requestForPermission($customer_id)
    {
        $customer = Customer::find($customer_id);
        $items = Item::where('customer_id', $customer_id)->where('is_valuation_approved', 'Y')->where('is_fee_structure_approved', 'Y')->where('is_hotlotz_own_stock', '!=', 'Y')->where('permission_to_sell', '!=', 'Y')->get();
        // dd(count($items));

        if (count($items)>0) {
            event( new SellerAgreementEvent($customer_id) );
        }

        // return redirect(route('customer.customers.show', $customer));
        return redirect(route('customer.customers.show_customer', [$customer, 'contact_details']));

    }

    public function checkTab(Request $request)
    {
        session(['customer_tab'=> $request->customer_tab]);

        return response()->json(array('status'=>1,'message'=>'Check Tab Successfully!'));
    }

    public function addressCreate(Request $request)
    {
        $customer_id = $request->customer_id;
        $customer = Customer::find($customer_id);
        $address_payload = [];
        $is_primary = 0;

        DB::beginTransaction();
        try {
            if ($request->type == 'correspondence') {
                $customer_address = [
                    'country_id'=>$request->country_id,
                    'address1'=>$request->address,
                    'city'=>$request->city,
                    'state'=>$request->state,
                    'postal_code'=>$request->postalcode,
                ];

                if ($request->country_id == '702') {
                    $customer_address['buyer_gst_registered'] = 1;
                }else{
                    $customer_address['buyer_gst_registered'] = 0;
                }

                $this->customerRepository->update($customer_id, $customer_address);
            }

            if ($request->type == 'correspondence' || $request->type == 'kyc') {
                $address_payload = [
                    'type'=>$request->type,
                    'firstname'=>$customer->firstname,
                    'lastname'=>$customer->lastname,
                    'address'=>$request->address,
                    'city'=>$request->city,
                    'state'=>$request->state,
                    'country_id'=>$request->country_id,
                    'postalcode'=>$request->postalcode,
                ];
            }

            // if ($request->type == 'shipping') {
            //     $address_payload = [
            //         'type'=>$request->type,
            //         'address_nickname'=>($request->type == 'shipping')?$request->shipping_address_nickname:null,
            //         'firstname'=>$request->shipping_firstname,
            //         'lastname'=>$request->shipping_lastname,
            //         'address'=>$request->shipping_address,
            //         'city'=>$request->shipping_city,
            //         'state'=>$request->shipping_state,
            //         'country_id'=>$request->shipping_country_id,
            //         'postalcode'=>$request->shipping_postalcode,
            //         'daytime_phone'=>($request->type == 'shipping')?$request->shipping_daytime_phone:null,
            //         'delivery_instruction'=>($request->type == 'shipping')?$request->shipping_delivery_instruction:null,
            //     ];
            //     $is_primary = $request->shipping_is_primary ?? 0;
            // }

            if ($request->type == 'shipping') {
                $address_payload = [
                    'type'=>$request->type,
                    'address_nickname'=>$request->address_nickname,
                    'firstname'=>$request->firstname,
                    'lastname'=>$request->lastname,
                    'address'=>$request->address,
                    'city'=>$request->city,
                    'state'=>$request->state,
                    'country_id'=>$request->country_id,
                    'postalcode'=>$request->postalcode,
                    'daytime_phone'=>$request->daytime_phone,
                    'delivery_instruction'=>$request->delivery_instruction,
                ];
                $is_primary = $request->is_primary ?? 0;
            }

            if(count($address_payload) > 0){
                $address_id = DB::table('addresses')->insertGetId($address_payload + NHelpers::created_updated_at());

                $customer_address = [
                    'customer_id' => $customer_id,
                    'address_id' => $address_id,
                    'is_primary' => $is_primary,
                ];
                DB::table('customer_addresses')->insert($customer_address + NHelpers::created_updated_at());

                if ($is_primary == 1) {
                    DB::table('customer_addresses')->where('customer_id', $customer_id)->where('address_id', '!=', $address_id)->where('is_primary', 1)->update(['is_primary' => 0]);
                }
            }
            DB::commit();

            flash()->success(__('Address has been created'));
            return redirect(route('customer.customers.show_customer', [$customer, 'contact_details']));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect(route('customer.customers.show_customer', [$customer, 'contact_details']));
        }
    }

    public function getAddress(Request $request)
    {
        try {
            $customer_id = $request->customer_id;
            $address_id = $request->address_id;
            $customer = Customer::find($customer_id);

            $address_detail = DB::table('addresses')->where('addresses.id', $address_id)
                    ->whereNull('addresses.deleted_at')
                    ->join('customer_addresses', 'customer_addresses.address_id', 'addresses.id')
                    ->where('customer_addresses.customer_id', $customer_id)
                    ->whereNull('customer_addresses.deleted_at')
                    ->select('addresses.*', 'customer_addresses.customer_id', 'customer_addresses.is_primary')
                    ->first();

            $countries = DB::table('countries')->orderBy('order_by_status', 'desc')->orderBy('name')->pluck('name', 'id')->all();

            $returnHTML = view('customer::show.edit_address_detail', [
                'type' => $request->type ?? $address_detail->type,
                'address_detail' => $address_detail,
                'countries' => $countries,
                'customer' => $customer,
            ])->render();

            return response()->json(array('status' => '1','message'=>'Get Address Successfully.', 'html'=>$returnHTML));
        } catch (Exception $e) {
            \Log::info('getAddress Error : '.print_r($e->getMessage(), true));
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    public function addressUpdate(Request $request)
    {
        $customer_id = $request->customer_id;
        $customer = Customer::find($customer_id);
        $is_primary = 0;

        DB::beginTransaction();
        try {
            $address_id = $request->address_id;

            if ($request->edit_type == 'correspondence') {
                $customer_address = [
                    'country_id'=>$request->country_id,
                    'address1'=>$request->address,
                    'city'=>$request->city,
                    'state'=>$request->state,
                    'postal_code'=>$request->postalcode,
                ];

                if ($request->country_id == '702') {
                    $customer_address['buyer_gst_registered'] = 1;
                }else{
                    $customer_address['buyer_gst_registered'] = 0;
                }

                $this->customerRepository->update($customer_id, $customer_address);
            }

            if ($request->edit_type == 'correspondence' || $request->edit_type == 'kyc') {
                $address_payload = [
                    'firstname'=>$customer->firstname,
                    'lastname'=>$customer->lastname,
                    'address'=>$request->address,
                    'city'=>$request->city,
                    'state'=>$request->state,
                    'country_id'=>$request->country_id,
                    'postalcode'=>$request->postalcode,
                ];
            }

            if ($request->edit_type == 'shipping') {
                $address_payload = [
                    'address_nickname'=>$request->address_nickname,
                    'firstname'=>$request->firstname,
                    'lastname'=>$request->lastname,
                    'address'=>$request->address,
                    'city'=>$request->city,
                    'state'=>$request->state,
                    'country_id'=>$request->country_id,
                    'postalcode'=>$request->postalcode,
                    'daytime_phone'=>$request->daytime_phone,
                    'delivery_instruction'=>$request->delivery_instruction,
                ];
                $is_primary = $request->is_primary ?? 0;
            }

            if(count($address_payload) > 0){
                DB::table('addresses')->where('id', $address_id)->update($address_payload + NHelpers::updated_at());

                if ($is_primary == 1) {
                    DB::table('customer_addresses')->where('customer_id', $customer_id)->where('address_id', $address_id)->update(['is_primary'=>1] + NHelpers::updated_at());

                    DB::table('customer_addresses')->where('customer_id', $customer_id)->where('address_id', '!=', $address_id)->where('is_primary', 1)->update(['is_primary' => 0]);
                }
            }
            DB::commit();


            flash()->success(__('Address has been updated'));
            return redirect(route('customer.customers.show_customer', [$customer, 'contact_details']));
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect(route('customer.customers.show_customer', [$customer, 'contact_details']));
        }
    }

    public function deleteAddress(Request $request)
    {
        $customer_id = $request->customer_id;
        $address_id = $request->address_id;

        try {
            $address = DB::table('addresses')->where('addresses.id',$address_id)->first();
            DB::table('customer_addresses')->where('address_id', $address_id)->delete();
            DB::table('addresses')->where('id', $address_id)->delete();

            if($address->type == 'correspondence'){
                $this->customerRepository->update($customer_id, [
                        'country_id' => null,
                        'address1' => null,
                        'city' => null,
                        'state' => null,
                        'postal_code' => null
                    ]);
            }

            return response()->json(array('status' => 'success','message'=>'Delete Address Successfully'));
        } catch (\Exception $e) {
            return \Response::json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }

    public function declineInvoice(Request $request)
    {
        $invoice_id = $request->invoice_id;
        try {
            $customerInvoice = CustomerInvoice::findOrFail($invoice_id);
            $customerInvoice->payment_processing = 0;
            $customerInvoice->payment_type = 'stripe';
            $customerInvoice->save();

            return response()->json(array('status' => '1','message'=>'Declined Invoice Successfully'));
        } catch (\Exception $e) {
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    public function search()
    {
        $customers = Customer::query()->whereNotNull('fullname')->where('fullname', 'LIKE', '%'.request('name') .'%')->orWhere('ref_no', 'LIKE', '%'.request('name') .'%')->orWhere('company_name', 'LIKE', '%'.request('name') .'%')->orWhere('email', 'LIKE', '%'.request('name') .'%')->get();
        $names = [];

        foreach($customers as $customer){
            $names[] = $customer->search_full_name;
        }

        return json_encode($names);
    }

    public function showCustomerFromTabname($customer_id)
    {
        session(['customer_tab'=> request()->tab_name]);
        return redirect(route('customer.customers.show', ['customer' => Customer::find($customer_id), 'order_id' => request()->order_id ]));
    }

    public function generateSaleroomReceipt(Customer $customer, Request $request)
    {
        $itemIds = json_decode($request->items);
        if($itemIds == null || count($itemIds) == 0){
            flash()->error(__('Error: :msg', ['msg' => 'At least check one item in item list']));
            return redirect()->back();
        }
        $items = Item::whereIn('id', $itemIds)->select('id', 'name', 'item_number')->orderBy('item_number','asc')->get();

        $data = [
            'customer' => $customer,
            'items' => $items,
            'receiveDate' => date('d/m/Y'),
            'receiveBy' => Auth::user()->name,
            'additional_note' => $request->additional_note
        ];

        $opciones_ssl=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $img_path = asset('ecommerce/images/logo/logo.jpg');
        $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
        $imgage = file_get_contents($img_path, false, stream_context_create($opciones_ssl));
        $img_base_64 = base64_encode($imgage);
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
        $data['logo'] = $path_img;

        $data['itemImage'] = [];
        foreach($items as $item){
            $itemImage = ItemImage::where('item_id',$item->id)->first();
            if ($itemImage) {
                $img_path = $itemImage->image_path;
            }else{
                $img_path = asset('images/default.jpg');
            }
            $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
            $imgage = @file_get_contents($img_path, false, stream_context_create($opciones_ssl));
            $img_base_64 = base64_encode($imgage);
            $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
            $data['itemImage'][] = $path_img;
        }

        view()->share('data',$data);
        $pdf = PDF::loadView('customer::pdf.saleroom_receipt_dom', $data);
        return $pdf->stream();

        // return view('customer::pdf.saleroom_receipt', $data);
    }

    public function generateSaleroomDispatch(Customer $customer, Request $request)
    {
        $itemIds = json_decode($request->items);
        if($itemIds == null || count($itemIds) == 0){
            flash()->error(__('Error: :msg', ['msg' => 'At least check one item in item list']));
            return redirect()->back();
        }
        $items = Item::whereIn('id', $itemIds)->select('id', 'name', 'item_number', 'status', 'lifecycle_status')->get();
        $data = [
            'customer' => $customer,
            'items' => $items,
            'receiveDate' => date('d/m/Y'),
            'additional_note' => $request->additional_note
        ];

        $opciones_ssl=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $img_path = asset('ecommerce/images/logo/logo.jpg');
        $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
        $imgage = @file_get_contents($img_path, false, stream_context_create($opciones_ssl));
        $img_base_64 = base64_encode($imgage);
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
        $data['logo'] = $path_img;

        $data['itemImage'] = [];
        $data['info'] = [];
        foreach ($items as $key => $item) {
            $itemImage = ItemImage::where('item_id', $item->id)->first();
            if($itemImage){
                $img_path = $itemImage->image_path;
            }
            $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
            $imgage = @file_get_contents($img_path, false, stream_context_create($opciones_ssl));
            $img_base_64 = base64_encode($imgage);
            $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
            $data['itemImage'][$key] = $path_img;

            $data['info'][$key] = ' ';
            if(in_array($item->status, [Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])){
                if($item->lifecycle_status == Item::_MARKETPLACE_ || $item->lifecycle_status == Item::_CLEARANCE_){
                    $data['info'][$key] = Item::_MARKETPLACE_;
                }
                if($item->lifecycle_status == Item::_AUCTION_){
                    $item_lifecycle = ItemLifecycle::where('item_id',$item->id)
                                    ->where('type','auction')
                                    ->whereIn('status',[Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])
                                    ->first();

                    if($item_lifecycle && isset($item_lifecycle) && !empty($item_lifecycle)){

                        $auction_item = AuctionItem::where('item_id', $item->id)
                                    ->where('auction_id',$item_lifecycle->reference_id)
                                    ->first();
                        if($auction_item && $auction_item->auction){
                            $data['info'][$key] = $auction_item->auction->title.' / Lot '.$auction_item->lot_number;
                        }
                    }
                }
            }
        }

        view()->share('data',$data);
        $pdf = PDF::loadView('customer::pdf.saleroom_dispatch_dom_two', $data);
        return $pdf->stream();

        // return view('customer::pdf.saleroom_dispatch', $data);
    }

    public function generateSellerReport(Customer $customer, Request $request)
    {
        $itemIds = json_decode($request->items);
        if($itemIds == null || count($itemIds) == 0){
            flash()->error(__('Error: :msg', ['msg' => 'At least check one item in item list']));
            return redirect()->back();
        }
        $items = Item::whereIn('id', $itemIds)->orderBy('item_number')->get();
        $data = [
            'customer' => $customer,
            'items' => $items,
            'receiveDate' => date('d/m/Y'),
        ];

        $opciones_ssl=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $img_path = asset('ecommerce/images/logo/logo.jpg');
        $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
        $imgage = @file_get_contents($img_path, false, stream_context_create($opciones_ssl));
        $img_base_64 = base64_encode($imgage);
        $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
        $data['logo'] = $path_img;

        $data['itemImage'] = [];
        foreach ($items as $item) {
            $itemImage = ItemImage::where('item_id', $item->id)->first();
            if($itemImage){
                $img_path = $itemImage->image_path;
            }
            $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
            $imgage = @file_get_contents($img_path, false, stream_context_create($opciones_ssl));
            $img_base_64 = base64_encode($imgage);
            $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;
            $data['itemImage'][] = $path_img;
        }

        view()->share('data',$data);
        $pdf = PDF::loadView('customer::pdf.generate_seller_report', $data);
        return $pdf->stream();

        // return view('customer::pdf.saleroom_dispatch', $data);
    }

    public function sendSaleroomReceipt(Customer $customer)
    {
        event(new DroppedOffItemReceivedEvent($customer->id));
        flash()->success(__('Saleroom receipt email has been send'));
        return redirect()->back();
    }

    public function remoteLogin(Customer $customer)
    {
        Auth::guard('customer')->login($customer);

        return redirect('/my-description');
    }

    public function splitSettlement(Customer $customer, $invoice_id)
    {
        $items = Item::where('bill_id', $invoice_id)->orderBy('sold_date','desc')->get();

        return view('customer::show.split_settlement', compact('items', 'invoice_id'));
    }

    public function getNote(Request $request)
    {
        try {
            $customer_id = $request->customer_id;
            $note_id = $request->note_id;

            $customer_note = CustomerNote::find($note_id);
            // dd($customer_note);

            $returnHTML = view('customer::show.edit_note_detail', [
                'customer_note' => $customer_note,
            ])->render();

            return response()->json(array('status' => '1','message'=>'Get Note Successfully.', 'html'=>$returnHTML));
        } catch (Exception $e) {
            \Log::info('getNote Error : '.print_r($e->getMessage(), true));
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    public function createNote(Request $request)
    {
        // dd($request->all());
        try {
            $customer_id = $request->customer_id;
            $customer = Customer::find($customer_id);
            $user_id = Auth::user()->id;
            $note_payload = [
                'customer_id'=>$customer_id,
                'user_id'=>$user_id,
                'note'=>$request->note,
            ];
            CustomerNote::create($note_payload);

            flash()->success(__('Note has been created'));
            return redirect(route('customer.customers.show_customer', [$customer,'notes']));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect(route('customer.customers.show_customer', [$customer,'notes']));
        }
    }

    public function updateNote(Request $request)
    {
        try {
            $note_id = $request->note_id;
            $customer_id = $request->customer_id;
            $customer = Customer::find($customer_id);
            $user_id = Auth::user()->id;
            $note_payload = [
                'customer_id'=>$customer_id,
                'user_id'=>$user_id,
                'note'=>$request->note,
            ];
            CustomerNote::where('id',$note_id)->update($note_payload);


            flash()->success(__('Note has been updated'));
            return redirect(route('customer.customers.show_customer', [$customer,'notes']));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect(route('customer.customers.show_customer', [$customer,'notes']));
        }
    }

    public function deleteNote(Request $request)
    {
        $customer_id = $request->customer_id;
        $note_id = $request->note_id;

        try {
            CustomerNote::where('id', $note_id)->where('customer_id', $customer_id)->delete();

            return response()->json(array('status' => '1','message'=>'Delete Successfully'));
        } catch (\Exception $e) {
            return \Response::json(array('status'=>'-1','message'=>$e->getMessage()));
        }
    }

    public function getSettlementList(Customer $customer, XeroControlRepository $xeroControlRepository)
    {
        $settlement_list = $customer->invoices()->where('type', 'bill')->get();
        $lastRelatedInvoice = [];

        foreach ($settlement_list as $settlement) {
            $relatedInvoicesId = Item::where('bill_id', $settlement->invoice_id)->pluck('invoice_id');
            $relatedCustomerInvoices = CustomerInvoice::whereIn('invoice_id', $relatedInvoicesId)->get();
            $relatedArray = [];

            foreach ($relatedCustomerInvoices as $relatedInvoice) {
                $relatedArrayData['ref'] = $relatedInvoice->invoice_number;
                $relatedArrayData['id'] = $relatedInvoice->id;

                $items = Item::where('invoice_id', $relatedInvoice->invoice_id)->get();
                foreach ($items as $item) {
                    $xeroControlRepository->createCustomerInvoiceItem($relatedInvoice->id, $item->id, $item->sold_price, $relatedInvoice->invoice_type);
                }

                $relatedItemData = new Collection();

                foreach ($relatedInvoice->items as $relatedItem) {
                    $relatedItemData = $relatedItemData->merge($relatedItem->item()->where('bill_id', $settlement->invoice_id)->select('name', 'item_number', 'status', 'id')->get());
                }
                $relatedArrayData['items'] = $relatedItemData;

                $relatedArray[] = $relatedArrayData;
            }
            $lastRelatedInvoice[$settlement->id] = $relatedArray;
        }

        $returnHTML = view('customer::show.ajaxblade.settlement_list', [
            'settlement_list' => $settlement_list,
            'lastRelatedInvoice' => $lastRelatedInvoice,
            'bank_account' => $customer->customer_bank_account,
            'customer' => $customer
        ])->render();

        return response()->json(array('status' => 'success', 'html'=>$returnHTML));
    }

    public function getInvoiceList(Customer $customer, XeroControlRepository $xeroControlRepository)
    {
        $invoice_list = $customer->invoices()->where('type', 'invoice')->get();
        $lastRelatedBill = [];

        foreach ($invoice_list as $invoice) {
            $relatedBillsId = Item::where('invoice_id', $invoice->invoice_id)->pluck('bill_id');
            $relatedCustomerBills = CustomerInvoice::whereIn('invoice_id', $relatedBillsId)->get();
            $relatedArray = [];
            foreach ($relatedCustomerBills as $relatedBill) {
                $relatedArrayData['ref'] = ($relatedBill->invoice_number != null) ? $relatedBill->invoice_number : "Settlement ".$relatedBill->id;
                $relatedArrayData['id'] = $relatedBill->id;

                $items = Item::where('bill_id', $relatedBill->invoice_id)->get();
                foreach($items as $item){
                     $xeroControlRepository->createCustomerInvoiceItem($relatedBill->id, $item->id, $item->sold_price);
                }

                $relatedItemData = new Collection();

                foreach ($relatedBill->items as $relatedItem) {
                    $relatedItemData = $relatedItemData->merge($relatedItem->item()->where('invoice_id', $invoice->invoice_id)->select('name', 'item_number', 'status', 'id')->get());
                }
                $relatedArrayData['items'] = $relatedItemData;

                $relatedArray[] = $relatedArrayData;
            }
            $lastRelatedBill[$invoice->id] = $relatedArray;
        }

        $returnHTML = view('customer::show.ajaxblade.invoice_list', [
            'invoice_list' => $invoice_list,
            'lastRelatedBill' => $lastRelatedBill,
            'customer' => $customer
        ])->render();

        return response()->json(array('status' => 'success', 'html'=>$returnHTML));
    }

    public function getAdhocInvoiceList(Customer $customer)
    {
        $adhoc_invoices = $customer->invoices()->where('invoice_type', 'adhoc')->get();
        $xero_items = DB::table('xero_items')->pluck('item_name', 'id')->all();

        $returnHTML = view('customer::adhoc_invoice.adhoc_invoice', [
            'adhoc_invoices' => $adhoc_invoices,
            'xero_items' => $xero_items,
        ])->render();

        return response()->json(array('status' => 'success', 'html'=>$returnHTML));
    }


    public function getPrivateInvoiceList(Customer $customer)
    {
        $private_invoices = $customer->invoices()->where('invoice_type', 'private')->get();
        $private_items = Item::where('lifecycle_id', '>', 0)
                    ->where('permission_to_sell', 'Y')->where('private_sale_type', null)
                    ->whereHas('lifecycle', function ($query) {
                        return $query->where('name', '=', 'Private Sale');
                    })
                    ->selectRaw("id, concat(name, ' (', item_number, ')') as custom_item_name")->pluck('custom_item_name', 'id');

        $returnHTML = view('customer::private_invoice.private_invoice', [
            'private_invoices' => $private_invoices,
            'private_items' => $private_items,
        ])->render();

        return response()->json(array('status' => 'success', 'html'=>$returnHTML));
    ## End - NRIC Document
    }

    public function sendKycSellerEmail($customer_id)
    {
        \Log::channel('emailLog')->info('Start - KYC Seller Email');

        $customer = Customer::find($customer_id);
        if($customer != null && $customer->type->value() == 'organization'){
            \Log::channel('emailLog')->info('sendKycSellerEmail : called SendKycCompanySellerEmailEvent for Client_'. $customer_id);
                event(new SendKycCompanySellerEmailEvent($customer_id));
        }
        if($customer != null && $customer->type->value() == 'individual'){
            \Log::channel('emailLog')->info('sendKycSellerEmail : called SendKycIndividualSellerEmailEvent for Client_'. $customer_id);
                event(new SendKycIndividualSellerEmailEvent($customer_id));
        }

        return redirect(route('customer.customers.show_customer', [$customer,'kyc']));
        \Log::channel('emailLog')->info('End - KYC Seller Email');
    }


    public function approveKyc($customer_id, Request $request)
    {
        try {
            if ($customer_id) {
                $payload = [
                    'kyc_approver_id'=>$request->kyc_approver_id,
                    'is_kyc_approved'=>'Y',
                    'kyc_approval_date' => date('Y-m-d H:i:s')
                ];
                $result = $this->customerRepository->update($customer_id, $payload);

                return response()->json(array('status'=>'success','message'=>'Approved KYC Successfully!'));
            }

            return response()->json(array('status'=>'failed','message'=>'Approved KYC Failed!'));
        } catch (Exception $e) {
            return response()->json(array('status'=>'failed','message'=>$e->getMessage()));
        }
    }
}