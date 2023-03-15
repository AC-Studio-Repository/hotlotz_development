@extends('appshell::layouts.default')

@section('title')
    {{ __('Auction Details') }}
@stop

@section('content')

    @yield('cards')

    <div class="card">
        <div class="card-block">
            @if( $auction->is_closed != 'Y' )

                @can('edit auctions')
                    <a href="{{ route('auction.auctions.edit', $auction) }}" class="btn btn-outline-primary">{{ __('Edit auction') }}</a>
                @endcan

                <a href="{{ route('auction.auctions.lot_list', $auction) }}" class="btn btn-outline-primary">{{ __('Lot Reorder') }}</a>

                @if($create_lot_count > 0)
                    <button type="button" class="btn btn-outline-primary" id="btnCreateLotsConfirm" data-id="{{ $auction->id }}" data-name="{{ $auction->title }}">{{ __('Create Lots into Toolbox') }}</button>
                @endif

                @if( $auction->publish_to_frontend != 'Y' )
                    <button type="button" class="btn btn-outline-primary" id="btnPublishToFrontend" data-id="{{ $auction->id }}">{{ __('Publish To Frontend') }}</button>
                @endif

                @if( $auction->publish_to_frontend == 'Y' )
                    <button type="button" class="btn btn-outline-primary" id="btnUnpublishToFrontend" data-id="{{ $auction->id }}">{{ __('Unpublish To Frontend') }}</button>
                @endif

                <button type="button" class="btn btn-outline-primary" id="btnLifecycleReset" data-id="{{ $auction->id }}">{{ __('Lifecycle Reset') }}</button>
            @endif

            @yield('actions')

            @can('delete auctions')
                <!-- <button type="button" class="btn btn-outline-danger float-right" id="btnDeleteConfirm" data-id="{{ $auction->id }}" data-name="{{ $auction->title }}" >{{ __('Delete Auction') }}</button> -->
            @endcan

            @if( $auction->publish_invoice($auction->id, 'local') > 0)
                <a href="{{ route('xero.publish.invoice', ['all', $auction->id, 'local']) }}"><button type="button" class="btn btn-outline-info">Publish Invoice (Local) ( {{ $auction->publish_invoice($auction->id, 'local') }} invoice(s) remaining )</button></a>
            @endif

            @if( $auction->publish_invoice($auction->id, 'foreign') > 0)
                <a href="{{ route('xero.publish.invoice', ['all', $auction->id, 'foreign']) }}"><button type="button" class="btn btn-outline-danger">Publish Invoice (Foreign) ( {{ $auction->publish_invoice($auction->id, 'foreign') }} invoice(s) remaining )</button></a>
            @endif

            <!-- @if( $auction->publish_bill($auction->id) > 0)
                <a href="{{ route('xero.publish.bill', ['all', $auction->id]) }}"><button type="button" class="btn btn-outline-warning">Publish Bill ( {{ $auction->publish_bill($auction->id) }} bill(s) remaining )</button></a>
            @endif -->

            @if( $lot_count > 0)
                <button type="button" class="btn btn-outline-primary" id="btnKycIndividualSellerEmails" data-id="{{ $auction->id }}" data-name="{{ $auction->title }}">{{ __('Send KYC Individual Seller Emails') }}</button>
                <button type="button" class="btn btn-outline-primary" id="btnKycCompanySellerEmails" data-id="{{ $auction->id }}" data-name="{{ $auction->title }}">{{ __('Send KYC Company Seller Emails') }}</button>
            @endif

            @if( $auction->is_closed == 'Y' && $lot_count > 0)
                <!-- <a href="{{ route('auction.auctions.send_kyc_buyer_email', $auction) }}"><button class="btn btn-outline-success">{{ __('Send KYC Buyer Emails') }}</button></a> -->
                <button type="button" class="btn btn-outline-primary" id="btnKycBuyerEmails" data-id="{{ $auction->id }}" data-name="{{ $auction->title }}">{{ __('Send KYC Buyer Emails') }}</button>
            @endif

        </div>
    </div>

    <div class="card">
        <div class="card-header font-sm">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link {{ ($tab_name == 'pre_auction')?'active':'' }}" id="pre_auction-tab" data-toggle="tab" href="{{ route('auction.auctions.show_auction', [$auction, 'pre_auction'] ) }}" role="tab" aria-controls="pre_auction" aria-selected="{{ ($tab_name == 'pre_auction')?'true':'false' }}" data-tab_name="pre_auction" onclick="clickTab(this)">{{ __('PRE-AUCTION') }}</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'auction_catalogue')?'active':'' }}" id="auction_catalogue-tab" data-toggle="tab" href="{{ route('auction.auctions.show_auction', [$auction, 'auction_catalogue'] ) }}" role="tab" aria-controls="auction_catalogue" aria-selected="{{ ($tab_name == 'auction_catalogue')?'true':'false' }}" data-tab_name="auction_catalogue" onclick="clickTab(this)">{{ __('AUCTION CATALOGUE') }}</a>

                    <a class="nav-item nav-link disabled" id="auction_dashboard-tab" data-toggle="tab" href="#auction_catalogue_block" role="tab" aria-controls="auction_dashboard" aria-selected="false" data-tab_name="auction_dashboard" style="cursor: not-allowed;pointer-events: all !important;">{{ __('AUCTION DASHBOARD') }}</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'post_auction')?'active':'' }}" id="post_auction-tab" data-toggle="tab" href="{{ route('auction.auctions.show_auction', [$auction, 'post_auction'] ) }}" role="tab" aria-controls="post_auction" aria-selected="{{ ($tab_name == 'post_auction')?'true':'false' }}" data-tab_name="post_auction" onclick="clickTab(this)">{{ __('POST-AUCTION') }}</a>
                </div>
            </nav>
        </div>
        <div class="card-block">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade {{ ($tab_name == 'pre_auction')?'show active':'' }}" id="pre_auction_block" role="tabpanel" aria-labelledby="pre_auction-tab">
                    @if($tab_name == 'pre_auction')
                        @include('auction::details.preauction')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'auction_catalogue')?'show active':'' }}" id="auction_catalogue_block" role="tabpanel" aria-labelledby="auction_catalogue-tab">
                    @if($tab_name == 'auction_catalogue')
                        <div class="lot_list_table_view"></div>
                        <div id="bidder_list_view"></div>
                        <div class="winner_list_view"></div>
                    @endif
                </div>
                <div class="tab-pane fade disabled" id="auction_dashboard_block" role="tabpanel" aria-labelledby="auction_dashboard-tab">
                    @if($tab_name == 'auction_dashboard')
                        @include('auction::details.dashboard')
                    @endif
                </div>

                <div class="tab-pane fade {{ ($tab_name == 'post_auction')?'show active':'' }}" id="post_auction_block" role="tabpanel" aria-labelledby="post_auction-tab">
                    @if($tab_name == 'post_auction')
                        @include('auction::details.postauction')
                        @include('auction::details.lot_list_table')
                        <!-- <div class="lot_list_table_view"></div> -->
                        <div class="winner_list_view"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
<link href="{{asset('plugins/select2-develop/dist/css/select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/select2-bootstrap4-theme-master/dist/select2-bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Select2 JS -->
<script src="{{asset('plugins/select2-develop/dist/js/select2.full.min.js')}}"></script>

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script type="text/javascript">
    var stepGetSaleReport = 0;
    var stepGetPreAuctionItem = 0;
    var stepGetLotList = 0;
    var auction_id =  '{!! $auction->id !!}';
    var _token = $('input[name="_token"]').val();
    var tab_name = '{!! $tab_name !!}';
    var is_closed = '{!! $auction->is_closed !!}';

    $(window).on('load', function() {
        if(tab_name == 'auction_catalogue'){
            getLotList();
            if(is_closed == 'Y'){
                getBidderList();
                getWinnerList();
            }
        }
        if(tab_name == 'post_auction'){
            getSelect2Customer();
            getSaleReport();
            $('#filter_sold_unsold').select2();
        }
    });

    function getSaleReport(timesRun = true, seller_id = null, status = null) {
        if (timesRun) {
            progressBar();
        }
        if(stepGetSaleReport == 0){
            $.ajax({
                url: '/manage/auctions/'+auction_id+'/getSaleReport',
                type: 'get',
                data: {
                    "seller_id": seller_id,
                    "status": status,
                    "_token": _token,
                },
                dataType: 'json',
                async: false,
                success: function(response) {
                    if(response.status == 'success'){
                        $('#sale_report_table_view').html(response.html);
                        $("img").bind("error",function(){
                            $(this).attr("src", "{{ asset('images/default.jpg') }}");
                        });                        
                        stepGetSaleReport = 1;
                    }
                }
            });
        }
    }

    function getPreAuctionItem(timesRun = true) {
        if (timesRun) {
            progressBar();
        }
        if(stepGetPreAuctionItem == 0){
            $.ajax({
                url: '/manage/auctions/'+auction_id+'/getPreAuctionItemReport',
                type: 'get',
                data: { },
                dataType: 'json',
                async: false,
                success: function(response) {
                    if(response.status == 'success'){
                        $('#pre_auction_item_table_view').html(response.html);
                        $("img").bind("error",function(){
                            $(this).attr("src", "{{ asset('images/default.jpg') }}");
                        });
                        stepGetPreAuctionItem = 1;
                    }
                }
            });
        }
    }

    function getLotList() {
        progressBar();
        if(stepGetLotList == 0){
            $.ajax({
                url: '/manage/auctions/'+auction_id+'/getLotList',
                type: 'get',
                data: { },
                dataType: 'json',
                async: false,
                success: function(response) {
                    if(response.status == 'success'){
                        $(".lot_list_table_view" ).each(function() {
                          $(this).html(response.html);
                        });
                        $("img").bind("error",function(){
                            $(this).attr("src", "{{ asset('images/default.jpg') }}");
                        });                        
                        stepGetLotList = 1;
                    }
                }
            });
        }
    }

    function getWinnerList() {
        $.ajax({
            url: '/manage/auctions/'+auction_id+'/getWinnerList',
            type: 'get',
            data: { },
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == 'success'){
                    $(".winner_list_view" ).each(function() {
                      $(this).html(response.html);
                    });
                    $("img").bind("error",function(){
                        $(this).attr("src", "{{ asset('images/default.jpg') }}");
                    });
                    stepGetLotList = 1;
                }
            }
        });
    }

    function getBidderList() {
        $.ajax({
            url: '/manage/auctions/'+auction_id+'/getBidderList',
            type: 'get',
            data: { },
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == 'success'){
                    $('#bidder_list_view').html(response.html);
                    $("img").bind("error",function(){
                        $(this).attr("src", "{{ asset('images/default.jpg') }}");
                    });
                    stepGetLotList = 1;
                }
            }
        });
    }

    function getSelect2Customer(){
        $.ajax({
            url: "/manage/customers/select2_all_customer",
            type: 'get',
            data: {'auction_id' : auction_id},
            dataType: 'json',
            async: false,
            success: function(data) {
                // $('#filter_seller').select2({data:data});
                $('#filter_seller').select2({data:data.result});
            }
        });
    }

    function getTotalSettlement(){
        $.ajax({
            url: '/manage/auctions/'+auction_id+'/getTotalSettlement',
            type: 'get',
            data: { },
            dataType: 'json',
            async: false,
            success: function(response) {
                $('#total_settlement').text(response.data)
            }
        });
    }

    $(function(){
        $(document).on('click', '#btnDeleteConfirm', function(){
            var auction_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to delete '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/auctions/'+auction_id,
                    type: 'delete',
                    data: {
                        "id": auction_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.alert(response.message, function(){
                                window.location.href = "{{ route('auction.auctions.index')}}";
                            });
                        }else {
                            bootbox.alert(response.message);
                            return false;
                        }
                    }
                });
            }
        });

        $(document).on('click', '#btnCreateLotsConfirm', function(){
            $(this).attr('disabled','disabled');
            var auction_id = $(this).attr('data-id');
            $.ajax({
                url: '/manage/auctions/'+auction_id+'/create_lots_into_toolbox',
                type: 'post',
                data: {
                    "_token": _token,
                },
                dataType: 'json',
                async: false,
                success: function(response) {
                    if(response.status == '-2') {
                        var link = "{{ route('auction.auctions.show_no_permission_items', $auction->id) }}";
                        var dialog = bootbox.dialog({
                            title: "Don't allow to create lots into Toolbox",
                            message: '<p>'+response.message+' <a href="'+link+'" style="color:red;" target="_self">Please click to see no permission items</a></p>',
                            size: 'large',
                            closeButton: false,
                            buttons: {
                                cancel: {
                                    label: "Cancel",
                                    className: 'btn-danger',
                                    callback: function(){
                                        console.log('Custom cancel clicked');
                                    }
                                }
                            }
                        });
                    }
                    if(response.status == 'success') {
                        $('#btnCreateLotsConfirm').hide();
                        bootbox.alert(response.message, function(){
                            window.location.reload();
                        });
                    }
                    if(response.status == '-1') {
                        bootbox.alert(response.message);
                        return false;
                    }
                }
            });
        });

        $('#filter_seller').change(function () {
            var seller_id = $(this).val();
            var status = $('#filter_sold_unsold').val();
            stepGetSaleReport = 0;
            getSaleReport(true, seller_id, status);
        });

        $('#filter_sold_unsold').change(function () {
            var seller_id = $('#filter_seller').val();
            var status = $(this).val();
            stepGetSaleReport = 0;
            getSaleReport(true, seller_id, status);
        });

        var timesRun = false;
        // var interval = setInterval(function(){
        //     if(timesRun){
        //         clearInterval(interval);
        //         return;
        //     }
        //     getSaleReport(timesRun);
        //     getPreAuctionItem(timesRun);
        //     timesRun = true;
        // }, 8000);

        $('#filter_seller').select2();

        $(document).on('click', '#btnPublishToFrontend', function(){
            var auction_id = $(this).attr('data-id');
            $.ajax({
                url: '/manage/auctions/'+auction_id+'/publish_to_frontend',
                type: 'post',
                data: {
                    "_token": _token,
                },
                dataType: 'json',
                async: false,
                success: function(response) {
                    if(response.status == 'success') {
                        $('#btnPublishToFrontend').hide();
                        bootbox.alert(response.message, function(){
                            window.location.reload();
                        });
                    }
                    if(response.status == 'failed') {
                        bootbox.alert(response.message);
                        return false;
                    }
                }
            });
        });

        $(document).on('click', '#btnUnpublishToFrontend', function(){
            var auction_id = $(this).attr('data-id');
            $.ajax({
                url: '/manage/auctions/'+auction_id+'/unpublish_to_frontend',
                type: 'post',
                data: {
                    "_token": _token,
                },
                dataType: 'json',
                async: false,
                success: function(response) {
                    if(response.status == 'success') {
                        $('#btnUnpublishToFrontend').hide();
                        bootbox.alert(response.message, function(){
                            window.location.reload();
                        });
                    }
                    if(response.status == 'failed') {
                        bootbox.alert(response.message);
                        return false;
                    }
                }
            });
        });

        var consigned_item_id;
        $(document).on("click", ".switch-input", function() {
            document.querySelectorAll('input.switch-input').forEach(elem => {
                elem.disabled = true;
            });
            consigned_item_id = $(this).data('id');

            var status = $(this).prop('checked');

            if(status == true) {
                status = 1;
            } else {
                status = 0;
            }

            $.ajax ({
                url: '/manage/items/'+consigned_item_id+'/getRecentlyConsigned',
                type: 'POST',
                data: {"id": consigned_item_id, "status": status, "_token": '{{ csrf_token() }}'},
                success: function(response) {
                    if(response.status == 'success'){
                        document.querySelectorAll('input.switch-input').forEach(elem => {
                            elem.disabled = false;
                        });
                    }else{
                        bootbox.alert('Internal sever error.');
                        document.querySelectorAll('input.switch-input').forEach(elem => {
                            elem.disabled = false;
                        });
                    }
                }
            });

        });

        $(document).on('click', '#btnKycIndividualSellerEmails', function(){
            console.log('Call Ajax - Send KYC Individual Seller Emails');
            var auction_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to send KYC Individual Seller Emails for Auction "'+name+'"?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: "{{ route('auction.auctions.send_kyc_individual_seller_email', $auction) }}",
                    type: 'get',
                    async: false,
                    success: function(response) {
                        console.log('Success - Send KYC Individual Seller Emails');
                    }
                });
            }
        });

        $(document).on('click', '#btnKycCompanySellerEmails', function(){
            console.log('Call Ajax - Send KYC Company Seller Emails');
            var auction_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to send KYC Company Seller Emails for Auction "'+name+'"?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: "{{ route('auction.auctions.send_kyc_company_seller_email', $auction) }}",
                    type: 'get',
                    async: false,
                    success: function(response) {
                        console.log('Success - Send KYC Company Seller Emails');
                    }
                });
            }
        });

        $(document).on('click', '#btnKycBuyerEmails', function(){
            console.log('Call Ajax - Send KYC Buyer Emails');
            var auction_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to send KYC Buyer Emails for Auction "'+name+'"?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: "{{ route('auction.auctions.send_kyc_buyer_email', $auction) }}",
                    type: 'get',
                    async: false,
                    success: function(response) {
                        console.log('Success - Send KYC Buyer Emails');
                    }
                });
            }
        });

        $(document).on('click', '#btnLifecycleReset', function(){
            console.log('Call Ajax - Lifecycle Reset For All Items');
            var auction_id = $(this).attr('data-id');

            $.ajax({
                url: "{{ route('auction.auctions.lifecycle_reset', $auction) }}",
                type: 'get',
                async: false,
                success: function(response) {
                    console.log('Success - Lifecycle Reset For All Items');
                    bootbox.alert(response.message);
                }
            });
        });
    });

    function clickTab(obj){
        var aria_selected = $(obj).attr('aria-selected');
        if(aria_selected == 'false'){
            var url = $(obj).attr('href');
            location.href = url;
        }
    }
</script>
@stop