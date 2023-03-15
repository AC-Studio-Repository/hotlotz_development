@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $customer->ref_no }}
@stop

@php
    $customer_tab = session()->has('customer_tab')?session('customer_tab'):'contact_details';
@endphp

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header font-sm">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{ ($tab_name == 'contact_details')?'active':'' }}" id="contact_details-tab" data-toggle="tab" href="{{ route('customer.customers.edit_customer', [$customer, 'contact_details'] ) }}" role="tab" aria-controls="contact_details" aria-selected="{{ ($tab_name == 'contact_details')?'true':'false' }}" data-tab_name="contact_details" onclick="clickTab(this)">{{ __('Contact Details') }}</a>
                <a class="nav-item nav-link {{ ($tab_name == 'seller_details')?'active':'' }}" id="seller_details-tab" data-toggle="tab" href="{{ route('customer.customers.edit_customer', [$customer, 'seller_details'] ) }}" role="tab" aria-controls="seller_details" aria-selected="{{ ($tab_name == 'seller_details')?'true':'false' }}" data-tab_name="seller_details" onclick="clickTab(this)">{{ __('Seller Details') }}</a>
                <a class="nav-item nav-link {{ ($tab_name == 'buyer_details')?'active':'' }}" id="buyer_details-tab" data-toggle="tab" href="{{ route('customer.customers.edit_customer', [$customer, 'buyer_details'] ) }}" role="tab" aria-controls="buyer_details" aria-selected="{{ ($tab_name == 'buyer_details')?'true':'false' }}" data-tab_name="buyer_details" onclick="clickTab(this)">{{ __('Buyer Details') }}</a>
                <a class="nav-item nav-link {{ ($tab_name == 'marketing')?'active':'' }}" id="marketing-tab" data-toggle="tab" href="{{ route('customer.customers.edit_customer', [$customer, 'marketing'] ) }}" role="tab" aria-controls="marketing" aria-selected="{{ ($tab_name == 'marketing')?'true':'false' }}" data-tab_name="marketing" onclick="clickTab(this)">{{ __('Marketing') }}</a>
                <a class="nav-item nav-link {{ ($tab_name == 'documents')?'active':'' }}" id="documents-tab" data-toggle="tab" href="{{ route('customer.customers.edit_customer', [$customer, 'documents'] ) }}" role="tab" aria-controls="documents" aria-selected="{{ ($tab_name == 'documents')?'true':'false' }}" data-tab_name="documents" onclick="clickTab(this)">{{ __('Documents') }}</a>
                <a class="nav-item nav-link {{ ($tab_name == 'adhoc_invoice')?'active':'' }}" id="adhoc_invoice-tab" data-toggle="tab" href="{{ route('customer.customers.edit_customer', [$customer, 'adhoc_invoice'] ) }}" role="tab" aria-controls="adhoc_invoice" aria-selected="{{ ($tab_name == 'adhoc_invoice')?'true':'false' }}" data-tab_name="adhoc_invoice" onclick="clickTab(this)">{{ __('Ad-hoc Invoice') }}</a>
                <a class="nav-item nav-link {{ ($tab_name == 'private_invoice')?'active':'' }}" id="private_invoice-tab" data-toggle="tab" href="{{ route('customer.customers.edit_customer', [$customer, 'private_invoice'] ) }}" role="tab" aria-controls="private_invoice" aria-selected="{{ ($tab_name == 'private_invoice')?'true':'false' }}" data-tab_name="private_invoice" onclick="clickTab(this)">{{ __('Private Invoice') }}</a>
                <a class="nav-item nav-link {{ ($tab_name == 'payments')?'active':'' }}" id="payments-tab" data-toggle="tab" href="{{ route('customer.customers.edit_customer', [$customer, 'payments'] ) }}" role="tab" aria-controls="payments" aria-selected="{{ ($tab_name == 'payments')?'true':'false' }}" data-tab_name="payments" onclick="clickTab(this)">{{ __('Payment Details') }}</a>
                <a class="nav-item nav-link {{ ($tab_name == 'notes')?'active':'' }}" id="notes-tab" data-toggle="tab" href="{{ route('customer.customers.edit_customer', [$customer, 'notes'] ) }}" role="tab" aria-controls="notes" aria-selected="{{ ($tab_name == 'notes')?'true':'false' }}" data-tab_name="notes" onclick="clickTab(this)">{{ __('Notes') }}</a>
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
                    <a class="nav-item nav-link {{ ($tab_name == 'kyc')?'active':'' }}" id="kyc-tab" data-toggle="tab" href="{{ route('customer.customers.edit_customer', [$customer, 'kyc'] ) }}" role="tab" aria-controls="kyc" aria-selected="{{ ($tab_name == 'kyc')?'true':'false' }}" data-tab_name="kyc" onclick="clickTab(this)" style="{{$style}}">{{ __('KYC') }}</a>
                @endif
            </div>
        </nav>
    </div>

    {!! Form::model($customer, ['route' => ['customer.customers.update', $customer,"tab_name"=>$tab_name], 'method' => 'PUT', 'id'=>'frmEditCustomer', 'data-parsley-validate'=>'true', 'autocomplete' => 'off','files' => 'true', 'enctype'=>'multipart/form-data', 'data-parsley-excluded'=>"input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden" ]) !!}

        <div>
            <input type="hidden" name="customer_id" id="customer_id" value="{{$customer->id}}">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade {{ ($tab_name == 'contact_details')?'show active':'' }}" id="contact_details" role="tabpanel" aria-labelledby="contact_details-tab">
                    @if($tab_name == 'contact_details')
                        @include('customer::contact_details')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'seller_details')?'show active':'' }}" id="seller_details" role="tabpanel" aria-labelledby="seller_details-tab">
                    @if($tab_name == 'seller_details')
                        @include('customer::seller_details')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'buyer_details')?'show active':'' }}" id="buyer_details" role="tabpanel" aria-labelledby="buyer_details-tab">
                    @if($tab_name == 'buyer_details')
                        @include('customer::buyer_details')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'marketing')?'show active':'' }}" id="marketing" role="tabpanel" aria-labelledby="marketing-tab">
                    @if($tab_name == 'marketing')
                        @include('customer::marketing')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'documents')?'show active':'' }}" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                    @if($tab_name == 'documents')
                        @include('customer::documents')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'adhoc_invoice')?'show active':'' }}" id="adhoc_invoice" role="tabpanel" aria-labelledby="adhoc_invoice-tab">
                    @if($tab_name == 'adhoc_invoice')
                        @include('customer::adhoc_invoice.show_adhoc_invoice')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'private_invoice')?'show active':'' }}" id="private_invoice" role="tabpanel" aria-labelledby="private_invoice-tab">
                    @if($tab_name == 'private_invoice')
                        @include('customer::private_invoice.show_private_invoice')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'payments')?'show active':'' }}" id="payments" role="tabpanel" aria-labelledby="payments-tab">
                    @if($tab_name == 'payments')
                        @include('customer::bank_detail')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'notes')?'show active':'' }}" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                    @if($tab_name == 'notes')
                        @include('customer::show_notes')
                    @endif
                </div>
                @if($customer->type->value() == 'individual')
                    <div class="tab-pane fade {{ ($tab_name == 'kyc')?'show active':'' }}" id="kyc" role="tabpanel" aria-labelledby="kyc-tab">
                        @if($tab_name == 'kyc')
                            @if($customer->type->value() == 'individual')
                                @include('customer::kyc_individual')
                            @endif
                            @if($customer->type->value() == 'organization')
                                @include('customer::kyc_company')
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-outline-success">{{ __('Update Client') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-outline-danger">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop

@section('scripts')

<!-- ### Additional CSS ### -->
<link href="{{asset('plugins/bootstrap-fileinput-5.0.8/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous"> -->
<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">


<!-- Select2 CSS -->
<link href="{{asset('plugins/select2-develop/dist/css/select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/select2-bootstrap4-theme-master/dist/select2-bootstrap4.min.css')}}" rel="stylesheet" />

<!-- Parsley CSS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">

<link rel="stylesheet" href="{{asset('/custom/css/bootstrap-duallistbox.css')}}">

<!-- Handlebars JS -->
<script src="{{asset('custom/js/handlebars-v4.7.3.min.js')}}"></script>


<!-- ### Additional JS ### -->
<!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you wish to resize images before upload. This must be loaded before fileinput.min.js -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/piexif.min.js')}}" type="text/javascript"></script>

<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
This must be loaded before fileinput.min.js -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/sortable.min.js')}}" type="text/javascript"></script>

<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for
HTML files. This must be loaded before fileinput.min.js -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/purify.min.js')}}" type="text/javascript"></script>

<!-- popper.min.js below is needed if you use bootstrap 4.x (for popover and tooltips). You can also use the bootstrap js js/plugins/purify.min.js
3.3.x versions without popper.min.js. -->
<script src="{{asset('custom/js/popper.min.js')}}"></script>

<!-- bootstrap.min.js below is needed if you wish to zoom and preview file content in a detail modal
dialog. bootstrap 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
<script src="{{asset('custom/js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>

<!-- the main fileinput plugin file -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/fileinput.min.js')}}"></script>

<!-- following theme script is needed to use the Font Awesome 5.x theme (`fas`) -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/themes/fas/theme.min.js')}}"></script>

<!-- optionally if you need translation for your language then include the locale file as mentioned below (replace LANG.js with your language locale) -->
<!-- <script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/locales/{LANG}.js')}}"></script> -->


<!-- Select2 JS -->
<script src="{{asset('plugins/select2-develop/dist/js/select2.full.min.js')}}"></script>
<!-- Parsley JS -->
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<script src="{{asset('/custom/js/jquery.bootstrap-duallistbox.js')}}"></script>

<!-- ### DatePicker JS ### -->
<script src="{{asset('custom/js/pickadate/lib/picker.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/picker.date.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/picker.time.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/legacy.js')}}"></script>


<script>
    new Vue({
        el: '#app',
        data: {
            customerType: '{{ old('type') ?: $customer->type->value() }}',
            formType: '{{ request()->segment(count(request()->segments())) }}',
            gstRegister: '{{ old('seller_gst_registered') ?: $customer->seller_gst_registered }}',
            citizenshipType: '{{ old('citizenship_type') ?: $customer->citizenship_type }}',
            idType: '{{ old('id_type') ?: $customer->id_type }}',
        }
    });

    var _token = $('input[name="_token"]').val();
    // var error_old_pwd = {!! json_encode($errors->has('old_password')) !!};
    // var error_new_pwd = {!! json_encode($errors->has('new_password')) !!};

    var is_active = {!! json_encode($customer->is_active) !!};
    var customer_tab = {!! json_encode($tab_name) !!};
    var form_type = {!! json_encode($form_type) !!};

    $(function(){

        checkStatus(is_active);
        $('#is_active').click(function(){
            checkStatus($(this).prop('checked'));
        });

        //#Dual Listbox for Marketing
        var category_interests = $('.category_interests').bootstrapDualListbox({
            nonSelectedListLabel: 'Available Categories',
            selectedListLabel: 'Selected Categories',
            moveOnSelect: false,
            showFilterInputs: false,
            // nonSelectedFilter: 'ion ([7-9]|[1][0-2])',
            // eventMoveOverride: true,
            // eventRemoveOverride: true,
            infoText: false,
        });

        $('[id="bootstrap-duallistbox-selected-list_category_interests[]"]').removeAttr('name');
    });
</script>

@include('customer::customer_js')

@stop
