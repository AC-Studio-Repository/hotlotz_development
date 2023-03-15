@extends('appshell::layouts.default')

@section('title')
    {{ app('request')->input('closed') == 'yes' ? 'Past Auctions' : 'Current Auctions' }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create auctions')
                <a href="{{ route('auction.auctions.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Auction') }}
                </a>
                @endcan
            </div>
        </div>

        <div class="card-block">
            @include('auction::auction_filter')
        </div>

        <div class="card-block" id="divAuctionList">
            @include('auction::_auction_index')
        </div>
        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    </div>
@stop

@section('scripts')

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>
<link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" />
<script src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $(function(){

        $('#btnSearch').click(function(){
            auctionFilter();
        });

        $('#per_page').change(function(){
            auctionFilter();
        });

        $('#search_text').keypress(function(event){
            var keyCode = (event.keyCode ? event.keyCode : event.which);
            if (keyCode == 13) {
                $('#btnSearch').trigger('click');
                return false;
            }
        });

        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page').val(page);

            $('li').removeClass('active');
            $(this).parent().addClass('active');
            auctionFilter(page);
        });

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const closed = urlParams.get('closed')
        const api = '/manage/auctions/search/title?closed='+closed;

        const auctions = [];

        fetch(api)
            .then(response => response.json())
            .then(blob => auctions.push(...blob));

        $( "#search_text" ).autocomplete({
            source: auctions
        });

        $(document).on('click', '#btnDeleteConfirm', function(){

            var auction_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to delete '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/auctions/'+auction_id+'/check_delete',
                    type: 'post',
                    data: {
                        "id": auction_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.confirm({
                                title: "Delete auction?",
                                message: response.message,
                                buttons: {
                                    confirm: {
                                        label: '<i class="fa fa-check"></i> Yes'
                                    },
                                    cancel: {
                                        label: '<i class="fa fa-times"></i> Cancel'
                                    }
                                },
                                callback: function (result) {
                                    console.log('result : ',result);
                                    if(result) {
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
                                                        location.reload();
                                                    });
                                                }else {
                                                    bootbox.alert(response.message);
                                                    return false;
                                                }
                                            }
                                        });
                                    }
                                }
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

    function auctionFilter(page=null)
    {
        var url = "/manage/auctions/filter";
        if(page != null){
            url = "/manage/auctions/filter?page="+page;
        }
        $.ajax({
            url:url,
            type: 'post',
            data: $('#auctionFilterForm').serialize()+"&_token="+_token,
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == '1'){
                    $('#divAuctionList').html(response.html);
                    $("img").bind("error",function(){
                        $(this).attr("src", "{{ asset('images/default.jpg') }}");
                    });
                }
            }
        });
    }
</script>
@stop