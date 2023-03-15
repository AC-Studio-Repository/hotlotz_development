@extends('appshell::layouts.default')

@section('title')
    {{ __('Client Details') }}
@stop

@section('styles')
<style>
.bootbox-input-textarea{
    height:142px;
}
.hiddenRow {
    padding: 0 4px !important;
    background-color: #eeeeee;
    font-size: 13px;
}
</style>
@endsection

@section('content')
    <div class="card">
        <div class="card-block">
            @can('edit customers')
                <a href="{{ route('customer.customers.edit_customer', [$customer, $tab_name]) }}"><button class="btn btn-outline-info">{{ __('Edit Client')}}</button></a>
            @endcan

            <a href="{{ route('item.items.add_item_from_client', $customer->id) }}"><button class="btn btn-outline-success">{{ __('Add an item') }}</button></a>

            @if($customer->type->value() == 'individual' && $customer->kyc_status != 'complete')
                <a href="{{ route('customer.customers.kyc_seller_email', $customer->id) }}"><button class="btn btn-outline-success">{{ __('Send KYC Seller Email') }}</button></a>
            @endif

            @can('delete customers')
                <button type="button" class="btn btn-outline-danger float-right" id="btnDeleteConfirm" data-id="{{ $customer->id }}" data-ref_no="{{ $customer->ref_no }}" data-fullname="{{ $customer->fullname }}" >{{ __('Delete') }}</button>
            @endcan
        </div>
    </div>
    <div class="card">
        <div class="card-header font-sm">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link {{ ($tab_name == 'contact_details')?'active':'' }}" id="contact_details-tab" data-toggle="tab" href="{{ route('customer.customers.show_customer', [$customer, 'contact_details'] ) }}" role="tab" aria-controls="contact_details" aria-selected="{{ ($tab_name == 'contact_details')?'true':'false' }}" data-tab_name="contact_details" onclick="clickTab(this)">{{ __('Contact Details') }}</a>
                    <a class="nav-item nav-link {{ ($tab_name == 'seller_details')?'active':'' }}" id="seller_details-tab" data-toggle="tab" href="{{ route('customer.customers.show_customer', [$customer, 'seller_details'] ) }}" role="tab" aria-controls="seller_details" aria-selected="{{ ($tab_name == 'seller_details')?'true':'false' }}" data-tab_name="seller_details" onclick="clickTab(this)">{{ __('Seller Details') }}</a>
                    <a class="nav-item nav-link {{ ($tab_name == 'buyer_details')?'active':'' }}" id="buyer_details-tab" data-toggle="tab" href="{{ route('customer.customers.show_customer', [$customer, 'buyer_details'] ) }}" role="tab" aria-controls="buyer_details" aria-selected="{{ ($tab_name == 'buyer_details')?'true':'false' }}" data-tab_name="buyer_details" onclick="clickTab(this)">{{ __('Buyer Details') }}</a>
                    <a class="nav-item nav-link {{ ($tab_name == 'marketing')?'active':'' }}" id="marketing-tab" data-toggle="tab" href="{{ route('customer.customers.show_customer', [$customer, 'marketing'] ) }}" role="tab" aria-controls="marketing" aria-selected="{{ ($tab_name == 'marketing')?'true':'false' }}" data-tab_name="marketing" onclick="clickTab(this)">{{ __('Marketing') }}</a>
                    <a class="nav-item nav-link {{ ($tab_name == 'documents')?'active':'' }}" id="documents-tab" data-toggle="tab" href="{{ route('customer.customers.show_customer', [$customer, 'documents'] ) }}" role="tab" aria-controls="documents" aria-selected="{{ ($tab_name == 'documents')?'true':'false' }}" data-tab_name="documents" onclick="clickTab(this)">{{ __('Documents') }}</a>
                    <a class="nav-item nav-link {{ ($tab_name == 'adhoc_invoice')?'active':'' }}" id="adhoc_invoice-tab" data-toggle="tab" href="{{ route('customer.customers.show_customer', [$customer, 'adhoc_invoice'] ) }}" role="tab" aria-controls="adhoc_invoice" aria-selected="{{ ($tab_name == 'adhoc_invoice')?'true':'false' }}" data-tab_name="adhoc_invoice" onclick="clickTab(this)">{{ __('Ad-hoc Invoice') }}</a>
                    <a class="nav-item nav-link {{ ($tab_name == 'private_invoice')?'active':'' }}" id="private_invoice-tab" data-toggle="tab" href="{{ route('customer.customers.show_customer', [$customer, 'private_invoice'] ) }}" role="tab" aria-controls="private_invoice" aria-selected="{{ ($tab_name == 'private_invoice')?'true':'false' }}" data-tab_name="private_invoice" onclick="clickTab(this)">{{ __('Private Invoice') }}</a>
                    <a class="nav-item nav-link {{ ($tab_name == 'payments')?'active':'' }}" id="payments-tab" data-toggle="tab" href="{{ route('customer.customers.show_customer', [$customer, 'payments'] ) }}" role="tab" aria-controls="payments" aria-selected="{{ ($tab_name == 'payments')?'true':'false' }}" data-tab_name="payments" onclick="clickTab(this)">{{ __('Payment Details') }}</a>
                    <a class="nav-item nav-link {{ ($tab_name == 'notes')?'active':'' }}" id="notes-tab" data-toggle="tab" href="{{ route('customer.customers.show_customer', [$customer, 'notes'] ) }}" role="tab" aria-controls="notes" aria-selected="{{ ($tab_name == 'notes')?'true':'false' }}" data-tab_name="notes" onclick="clickTab(this)">{{ __('Notes') }}</a>
                    @if($customer->type->value() == 'individual')
                        @php
                            $style = 'color: red;';
                            if($customer->kyc_status == 'partial' || $customer->kyc_status == 'complete'){
                                $style = 'color: green;';
                            }
                            if($customer->is_kyc_approved == 'Y'){
                                $style = '';
                            }
                        @endphp
                        <a class="nav-item nav-link {{ ($tab_name == 'kyc')?'active':'' }}" id="kyc-tab" data-toggle="tab" href="{{ route('customer.customers.show_customer', [$customer, 'kyc'] ) }}" role="tab" aria-controls="kyc" aria-selected="{{ ($tab_name == 'kyc')?'true':'false' }}" data-tab_name="kyc" onclick="clickTab(this)" style="{{$style}}">{{ __('KYC') }}</a>
                    @endif
                </div>
            </nav>
        </div>

        <div>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade {{ ($tab_name == 'contact_details')?'show active':'' }}" id="contact_details" role="tabpanel" aria-labelledby="contact_details-tab">
                    @if($tab_name == 'contact_details')
                        @include('customer::show.show_contact_details')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'seller_details')?'show active':'' }}" id="seller_details" role="tabpanel" aria-labelledby="seller_details-tab">
                    @if($tab_name == 'seller_details')
                        @include('customer::show.show_seller_details')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'buyer_details')?'show active':'' }}" id="buyer_details" role="tabpanel" aria-labelledby="buyer_details-tab">
                    @if($tab_name == 'buyer_details')
                        @include('customer::show.show_buyer_details')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'marketing')?'show active':'' }}" id="marketing" role="tabpanel" aria-labelledby="marketing-tab">
                    @if($tab_name == 'marketing')
                        @include('customer::show.show_marketing')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'documents')?'show active':'' }}" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                    @if($tab_name == 'documents')
                        @include('customer::show.show_document')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'adhoc_invoice')?'show active':'' }}" id="adhoc_invoice" role="tabpanel" aria-labelledby="adhoc_invoice-tab">
                    <!-- <div id="adhoc_invoice_view"></div> -->
                    @if($tab_name == 'adhoc_invoice')
                        @include('customer::adhoc_invoice.adhoc_invoice')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'private_invoice')?'show active':'' }}" id="private_invoice" role="tabpanel" aria-labelledby="private_invoice-tab">
                    <!-- <div id="private_invoice_view"></div> -->
                    @if($tab_name == 'private_invoice')
                        @include('customer::private_invoice.private_invoice')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'payments')?'show active':'' }}" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                    @if($tab_name == 'payments')
                        @include('customer::show.bank_detail')
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h5><strong>{{ __('Stripe Details') }}</strong></h5>
                                @include('stripe::payments.list', [ 'stripe_customer_id' => $customer->stripe_customer_id])
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'notes')?'show active':'' }}" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                    @if($tab_name == 'notes')
                        @include('customer::show.notes')
                    @endif
                </div>

                @if($customer->type->value() == 'individual')
                    <div class="tab-pane fade {{ ($tab_name == 'kyc')?'show active':'' }}" id="kyc" role="tabpanel" aria-labelledby="kyc-tab">
                        @if($tab_name == 'kyc')
                            @if($customer->type->value() == 'individual')
                                @include('customer::show.show_kyc_individual')
                            @endif
                            @if($customer->type->value() == 'organization')
                                @include('customer::show.show_kyc_individual')
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="card-footer">
            @yield('actions')
        </div>
    </div>

@stop

@section('scripts')
<!-- Parsley CSS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
<!-- Parsley JS -->
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<!-- Handlebars JS -->
<script src="{{asset('custom/js/handlebars-v4.7.3.min.js')}}"></script>

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>


<script type="text/javascript">
    var _token = $('input[name="_token"]').val();
    var customer_id = {!! json_encode($customer->id,true) !!};
    var customer_tab = {!! json_encode($tab_name) !!};

    var sell_item_list = [];
    var purchased_item_list = [];
    var stepSellerDetail = 0;
    var stepBuyerDetail = 0;
    var stepAdhocInvoice = 0;
    var stepPrivateInvoice = 0;
    var filter_seller_status = 'all';
    var filter_buyer_status = 'all';


    $(function(){

        $(document).on('click', '#btnDeleteConfirm', function(){
            var customer_id = $(this).attr('data-id');
            var ref_no = $(this).attr('data-ref_no');
            var fullname = $(this).attr('data-fullname');
            var content = 'Are you sure you want to delete '+ref_no+'_'+fullname+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/customers/'+customer_id,
                    type: 'delete',
                    data: {
                        "id": customer_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.alert(response.message, function(){
                                window.location.href = "{{ route('customer.customers.index')}}";
                            });
                        }else {
                            bootbox.alert(response.message);
                            return false;
                        }
                    }
                });
            }
        });

    });

    function clickTab(obj){
        var aria_selected = $(obj).attr('aria-selected');
        if(aria_selected == 'false'){
            var url = $(obj).attr('href');
            location.href = url;
        }
    }

    $('.icon_toggleable').on('click', function() {
        $(this).toggleClass('zmdi-plus zmdi-minus');
    });

    var timesRun = false;

</script>

@stop