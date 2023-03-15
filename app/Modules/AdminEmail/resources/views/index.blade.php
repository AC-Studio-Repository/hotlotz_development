@extends('appshell::layouts.default')

@section('title')
    {{ __('Admin Emails') }}
@stop

@section('content')


    {!! Form::open(['route' => 'admin_email.admin_emails.save', 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}
        <div class="card card-accent-secondary">
            <div class="card-header">
                Sell With Us Weekly Email
            </div>

            <div class="card-block">
                @include('admin_email::_sellwithus_form')
            </div>
        </div>

        <div class="card card-accent-secondary">
            <div class="card-header">
                Bank Account Update Alert Email
            </div>

            <div class="card-block">
                @include('admin_email::_bankaccount_form')
            </div>
        </div>

        <div class="card card-accent-secondary">
            <div class="card-header">
                Profile Update Alert Email
            </div>

            <div class="card-block">
                @include('admin_email::_profileupdate_form')
            </div>
        </div>

        <div class="card card-accent-secondary">
            <div class="card-header">
                Marketplace Sold Item List Email
            </div>

            <div class="card-block">
                @include('admin_email::_mp_sold_items_form')
            </div>
        </div>

        <div class="card card-accent-secondary">
            <div class="card-header">
                Items moved to Storage Email
            </div>

            <div class="card-block">
                @include('admin_email::_items_moved_to_storage_form')
            </div>
        </div>

        <div class="card card-accent-secondary">
            <div class="card-header">
                Sales Contract Alert Email
            </div>

            <div class="card-block">
                @include('admin_email::_salescontract_form')
            </div>
        </div>

        <div class="card card-accent-secondary">
            <div class="card-header">
                Bank Transfer/Paynow Checkout Alert Email
            </div>

            <div class="card-block">
                @include('admin_email::_bank_paynow_checkout_form')
            </div>
        </div>

        <div class="card card-accent-secondary">
            <div class="card-header">
                KYC Update Alert Email
            </div>

            <div class="card-block">
                @include('admin_email::_kyc_update_form')
            </div>
        </div>

        <div class="card">
            <div class="card-block">
                <button class="btn btn-primary">{{ __('Save emails') }}</button>
            </div>
        </div>
    {!! Form::close() !!}

@stop

@section('scripts')
<!-- Parsley -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}" />
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<!-- Handlebar JS -->
<script src="{{asset('custom/js/handlebars-v4.7.3.min.js')}}"></script>

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();
    var sellwithus_emails = {!! json_encode($sellwithus_emails) !!};
    var bankaccount_emails = {!! json_encode($bankaccount_emails) !!};
    var profileupdate_emails = {!! json_encode($profileupdate_emails) !!};
    var mpsolditems_emails = {!! json_encode($mpsolditems_emails) !!};
    var itemsmovedtostorage_emails = {!! json_encode($itemsmovedtostorage_emails) !!};
    var salescontract_emails = {!! json_encode($salescontract_emails) !!};
    var bank_paynow_checkout_emails = {!! json_encode($bank_paynow_checkout_emails) !!};
    var kyc_update_emails = {!! json_encode($kyc_update_emails) !!};
    var emailtemplate;

    var newsellwithusemail = {
        id:0,
        type:"swu",
        email:null,
    };
    // var newSWUdata = {};
    // $.extend(true, newSWUdata, newsellwithusemail, {'sellwithus_emails':sellwithus_emails});

    var newBankAccUpdateEmail = {
        id:0,
        type:"bau",
        email:null,
    };

    var newProfileUpdateEmail = {
        id:0,
        type:"profile",
        email:null,
    };

    var newMpSoldItemsEmail = {
        id:0,
        type:"mp_sold_items",
        email:null,
    };

    var newItemsMovedToStorageEmail = {
        id:0,
        type:"items_moved_to_storage",
        email:null,
    };

    var newSalesContractAlertEmail = {
        id:0,
        type:"sales_contract",
        email:null,
    };

    var newBankPaynowCheckoutEmail = {
        id:0,
        type:"bank_paynow_checkout",
        email:null,
    };

    var newKycUpdateEmail = {
        id:0,
        type:"kyc",
        email:null,
    };

    $(function(){

        Handlebars.registerHelper('if_eq', function(v1, v2, options) {
            if(v1 == v2) {
              return options.fn(this);
            }
            return  options.inverse(this);
        });

        Handlebars.registerHelper('if_not_eq', function(v1, v2, options) {
            if(v1 != v2) {
              return options.fn(this);
            }
            return  options.inverse(this);
        });

        Handlebars.registerHelper('ifInArray', function(v1, arr, options) {
            if($.inArray( v1, arr ) > -1) {
                return options.fn(this);
            }
            return options.inverse(this);
        });

        emailtemplate = Handlebars.compile($('#email_template').html());

        if($('#divSellWithUsEmail .divEmail').length == 0){
            $('#divSellWithUsEmail').html(emailtemplate(newsellwithusemail));
        }
        if($('#divBankAccUpdateEmail .divEmail').length == 0){
            $('#divBankAccUpdateEmail').html(emailtemplate(newBankAccUpdateEmail));
        }
        if($('#divProfileUpdateEmail .divEmail').length == 0){
            $('#divProfileUpdateEmail').html(emailtemplate(newProfileUpdateEmail));
        }
        if($('#divMpSoldItemsEmail .divEmail').length == 0){
            $('#divMpSoldItemsEmail').html(emailtemplate(newMpSoldItemsEmail));
        }
        if($('#divItemsMovedToStorageEmail .divEmail').length == 0){
            $('#divItemsMovedToStorageEmail').html(emailtemplate(newItemsMovedToStorageEmail));
        }
        if($('#divSalesContractEmail .divEmail').length == 0){
            $('#divSalesContractEmail').html(emailtemplate(newSalesContractAlertEmail));
        }
        if($('#divBankPaynowCheckoutEmail .divEmail').length == 0){
            $('#divBankPaynowCheckoutEmail').html(emailtemplate(newBankPaynowCheckoutEmail));
        }
        if($('#divKycUpdateEmail .divEmail').length == 0){
            $('#divKycUpdateEmail').html(emailtemplate(newKycUpdateEmail));
        }

        $.each(sellwithus_emails,function(i, email_data){
            var data = $.extend( true, {}, email_data );
            if(i == 0){
                $('#divSellWithUsEmail').html(emailtemplate(data));
            }else{
                $('#divSellWithUsEmail').append(emailtemplate(data));
            }
        });

        $.each(bankaccount_emails,function(i, email_data){
            var data = $.extend( true, {}, email_data );            
            if(i == 0){
                $('#divBankAccUpdateEmail').html(emailtemplate(data));
            }else{
                $('#divBankAccUpdateEmail').append(emailtemplate(data));
            }
        });

        $.each(profileupdate_emails,function(i, email_data){
            var data = $.extend( true, {}, email_data );            
            if(i == 0){
                $('#divProfileUpdateEmail').html(emailtemplate(data));
            }else{
                $('#divProfileUpdateEmail').append(emailtemplate(data));
            }
        });

        $.each(mpsolditems_emails,function(i, email_data){
            var data = $.extend( true, {}, email_data );            
            if(i == 0){
                $('#divMpSoldItemsEmail').html(emailtemplate(data));
            }else{
                $('#divMpSoldItemsEmail').append(emailtemplate(data));
            }
        });

        $.each(itemsmovedtostorage_emails,function(i, email_data){
            var data = $.extend( true, {}, email_data );            
            if(i == 0){
                $('#divItemsMovedToStorageEmail').html(emailtemplate(data));
            }else{
                $('#divItemsMovedToStorageEmail').append(emailtemplate(data));
            }
        });

        $.each(salescontract_emails,function(i, email_data){
            var data = $.extend( true, {}, email_data );            
            if(i == 0){
                $('#divSalesContractEmail').html(emailtemplate(data));
            }else{
                $('#divSalesContractEmail').append(emailtemplate(data));
            }
        });

        $.each(bank_paynow_checkout_emails,function(i, email_data){
            var data = $.extend( true, {}, email_data );            
            if(i == 0){
                $('#divBankPaynowCheckoutEmail').html(emailtemplate(data));
            }else{
                $('#divBankPaynowCheckoutEmail').append(emailtemplate(data));
            }
        });

        $.each(kyc_update_emails,function(i, email_data){
            var data = $.extend( true, {}, email_data );            
            if(i == 0){
                $('#divKycUpdateEmail').html(emailtemplate(data));
            }else{
                $('#divKycUpdateEmail').append(emailtemplate(data));
            }
        });

        $('#addSellWithUsButton').click(function(){
            AddNewEmail('#divSellWithUsEmail','swu');
        });
        $('#addBankAccUpdateButton').click(function(){
            AddNewEmail('#divBankAccUpdateEmail','bau');
        });
        $('#addProfileUpdateButton').click(function(){
            AddNewEmail('#divProfileUpdateEmail','profile');
        });
        $('#addMpSoldItemsButton').click(function(){
            AddNewEmail('#divMpSoldItemsEmail','mp_sold_items');
        });
        $('#addItemsMovedToStorageButton').click(function(){
            AddNewEmail('#divItemsMovedToStorageEmail','items_moved_to_storage');
        });
        $('#addSalesContractButton').click(function(){
            AddNewEmail('#divSalesContractEmail','sales_contract');
        });
        $('#addBankPaynowCheckoutButton').click(function(){
            AddNewEmail('#divBankPaynowCheckoutEmail','bank_paynow_checkout');
        });
        $('#addKycUpdateButton').click(function(){
            AddNewEmail('#divKycUpdateEmail','kyc');
        });

        $(document).on( "click", ".removeSellWithUsButton", function(e){
            e.preventDefault();
            var admin_email_id = $(this).parents('.divEmail').children('.admin_email_id').val();
            if($('#divSellWithUsEmail .divEmail').length > 1){
                $(this).parents('.divEmail').remove();
                if(admin_email_id != 0) {
                    deleteEmail(admin_email_id);
                }
            }else{
                alert('Please fill at least one Sell With Us Email.');
            }
        });

        $(document).on( "click", ".removeBankAccUpdateButton", function(e){
            e.preventDefault();
            var admin_email_id = $(this).parents('.divEmail').children('.admin_email_id').val();
            if($('#divBankAccUpdateEmail .divEmail').length > 1){
                $(this).parents('.divEmail').remove();
                if(admin_email_id != 0) {
                    deleteEmail(admin_email_id);
                }
            }else{
                alert('Please fill at least one Bank Account Update Email.');
            }
        });

        $(document).on( "click", ".removeProfileUpdateButton", function(e){
            e.preventDefault();
            var admin_email_id = $(this).parents('.divEmail').children('.admin_email_id').val();
            if($('#divProfileUpdateEmail .divEmail').length > 1){
                $(this).parents('.divEmail').remove();
                if(admin_email_id != 0) {
                    deleteEmail(admin_email_id);
                }
            }else{
                alert('Please fill at least one Profile Update Email.');
            }
        });

        $(document).on( "click", ".removeMpSoldItemsButton", function(e){
            e.preventDefault();
            var admin_email_id = $(this).parents('.divEmail').children('.admin_email_id').val();
            if($('#divMpSoldItemsEmail .divEmail').length > 1){
                $(this).parents('.divEmail').remove();
                if(admin_email_id != 0) {
                    deleteEmail(admin_email_id);
                }
            }else{
                alert('Please fill at least one MarketplaceSoldItems Email.');
            }
        });

        $(document).on( "click", ".removeItemsMovedToStorageButton", function(e){
            e.preventDefault();
            var admin_email_id = $(this).parents('.divEmail').children('.admin_email_id').val();
            if($('#divItemsMovedToStorageEmail .divEmail').length > 1){
                $(this).parents('.divEmail').remove();
                if(admin_email_id != 0) {
                    deleteEmail(admin_email_id);
                }
            }else{
                alert('Please fill at least one ItemsMovedToStorage Email.');
            }
        });

        $(document).on( "click", ".removeSalesContractButton", function(e){
            e.preventDefault();
            var admin_email_id = $(this).parents('.divEmail').children('.admin_email_id').val();
            if($('#divSalesContractEmail .divEmail').length > 1){
                $(this).parents('.divEmail').remove();
                if(admin_email_id != 0) {
                    deleteEmail(admin_email_id);
                }
            }else{
                alert('Please fill at least one SalesContractAlert Email.');
            }
        });

        $(document).on( "click", ".removeBankPaynowCheckoutButton", function(e){
            e.preventDefault();
            var admin_email_id = $(this).parents('.divEmail').children('.admin_email_id').val();
            if($('#divBankPaynowCheckoutEmail .divEmail').length > 1){
                $(this).parents('.divEmail').remove();
                if(admin_email_id != 0) {
                    deleteEmail(admin_email_id);
                }
            }else{
                alert('Please fill at least one Bank Transfer/Paynow Checkout Email.');
            }
        });

        $(document).on( "click", ".removeKycUpdateButton", function(e){
            e.preventDefault();
            var admin_email_id = $(this).parents('.divEmail').children('.admin_email_id').val();
            if($('#divKycUpdateEmail .divEmail').length > 1){
                $(this).parents('.divEmail').remove();
                if(admin_email_id != 0) {
                    deleteEmail(admin_email_id);
                }
            }else{
                alert('Please fill at least one KYC Update Alert Email.');
            }
        });

    });


    function AddNewEmail(obj, type) {
        if(type == 'swu'){
            $(obj).append( emailtemplate(newsellwithusemail) );
        }
        if(type == 'bau'){
            $(obj).append( emailtemplate(newBankAccUpdateEmail) );
        }
        if(type == 'profile'){
            $(obj).append( emailtemplate(newProfileUpdateEmail) );
        }
        if(type == 'mp_sold_items'){
            $(obj).append( emailtemplate(newMpSoldItemsEmail) );
        }
        if(type == 'items_moved_to_storage'){
            $(obj).append( emailtemplate(newItemsMovedToStorageEmail) );
        }
        if(type == 'sales_contract'){
            $(obj).append( emailtemplate(newSalesContractAlertEmail) );
        }
        if(type == 'bank_paynow_checkout'){
            $(obj).append( emailtemplate(newBankPaynowCheckoutEmail) );
        }
        if(type == 'kyc'){
            $(obj).append( emailtemplate(newKycUpdateEmail) );
        }
    }

    function deleteEmail(admin_email_id) {
        $.ajax({
            url: "/manage/admin_emails/"+admin_email_id,
            type: 'delete',
            data: {
                "id": admin_email_id,
                "_token": _token,
            },
            dataType: 'json',
            async: false,
            success: function(data) {
                //
            }
        });
    }

</script>
@include('admin_email::email_template')
@stop