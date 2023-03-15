@extends('appshell::layouts.default')

@section('title')
    {{ __('Sold Items') }}
@stop

@section('styles')
<style>
.bootbox-input-textarea{
    height:142px;
}
</style>
@endsection

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        <div class="card-block">
            @include('marketplace::sold_item_filter')
        </div>

        <div class="card-block" id="divItemList">
            <div class="table table-striped table-hover table-responsive" style="overflow-x:auto;">
                <table class="table table-striped" width="100%">
                    <thead>
                        <tr>
                            <th width="1%">
                                {{ Form::checkbox('sold_item_all', 'Y', false, [
                                        'id' => "sold_item_all",
                                    ])
                                }}
                            </th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Ref. Number') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Seller\'s Name') }}</th>
                            <th>{{ __('Buy Now Price') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Date entered') }}</th>
                            <th width="10%" class="sorting" data-sorting_type="asc" data-column_name="items.sold_date" data-icon_name="sold_date" style="cursor: pointer">{{ __('Sold Date') }} <span id="sold_date_icon"></span></th>
                            <th>{{ __('Result Price') }}</th>
                            <th>{{ __('Buyer\'s Name') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @include('marketplace::sold_item_table')
                    </tbody>
                </table>
            </div>
        </div>

        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
        <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
        <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
    </div>

@stop

@section('scripts')

<!-- Select2 CSS -->
<link href="{{asset('plugins/select2-develop/dist/css/select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/select2-bootstrap4-theme-master/dist/select2-bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Select2 JS -->
<script src="{{asset('plugins/select2-develop/dist/js/select2.full.min.js')}}"></script>

<!-- ### Additional CSS ### -->
<link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" />
<script src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">
<!-- ### Additional JS ### -->
<script src="{{ asset('fontawesome/js/all.min.js') }}"></script>


<!-- Bootbox JS -->
<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script type="text/javascript">

    var _token = $('input[name="_token"]').val();
    var item_list = [];
    var sold_item_selected_all = {!! json_encode($sold_item_selected_all) !!};

    $(function(){

        checkSoldItemSelectAll(sold_item_selected_all);

        function clear_icon()
        {
            $('#sold_date_icon').html('');
        }

        $('#btnResetAll').click(function(){
            location.reload();
        });

        // $.fn.select2.defaults.set("placeholder", 'All');
        // fnCallbackSeller(false);
        // fnCallbackBuyer(false);
        customerSelect2('#seller');
        customerSelect2('#buyer');

        $('#seller, #buyer, #tag, #per_page').change(function(){
            var sort_type = $('#hidden_sort_type').val();
            var sort_by = $('#hidden_column_name').val();
            filterItem(sort_type, sort_by);
        });

        $('#btnSearch').click(function(){
            var sort_type = $('#hidden_sort_type').val();
            var sort_by = $('#hidden_column_name').val();
            filterItem(sort_type, sort_by);
        });

        $(document).on('click', '.sorting', function(){
            var icon_name = $(this).attr('data-column_name');
            var column_name = $(this).attr('data-column_name');
            var order_type = $(this).attr('data-sorting_type');
            var sort_type = '';
            if(order_type == 'asc')
            {
                $(this).attr('data-sorting_type','desc');
                sort_type = 'desc';
                clear_icon();
                $('#'+icon_name+'_icon').html('<i class="fas fa-angle-down"></i>');
            }
            if(order_type == 'desc')
            {
                $(this).attr('data-sorting_type','asc');
                sort_type = 'asc';
                clear_icon
                $('#'+icon_name+'_icon').html('<i class="fas fa-angle-up"></i>');
            }
            $('#hidden_column_name').val(column_name);
            $('#hidden_sort_type').val(sort_type);
            var page = $('#hidden_page').val();
            filterItem(sort_type, column_name, page);
        });

        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page').val(page);
            var sort_type = $('#hidden_sort_type').val();
            var sort_by = $('#hidden_column_name').val();

            $('li').removeClass('active');
            $(this).parent().addClass('active');
            filterItem(sort_type, sort_by, page);
        });

        $(document).on('click', '#btnWithdrawn', function(){
            var item_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to withdraw '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: "/manage/items/"+item_id+"/withdraw",
                    type: 'post',
                    data: "_token="+_token,
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        if(data.status == 'success'){
                            location.reload(true);
                        }
                    }
                });
            }
        });

        // $(document).on('click', '#btnHighlight', function(){
        //     var item_id = $(this).attr('data-id');
        //     var name = $(this).attr('data-name');
        //     var content = 'Are you sure to set highlight of '+name+'?';

        //     var response = confirm(content);
        //     if (response == true) {
        //         $.ajax({
        //             url: "/manage/items/"+item_id+"/set_highlight",
        //             type: 'post',
        //             data: "_token="+_token,
        //             dataType: 'json',
        //             async: false,
        //             success: function(data) {
        //                 if(data.status == 'success'){
        //                     location.reload(true);
        //                 }
        //             }
        //         });
        //     }
        // });

        $(document).on('click', '.item_id', function(){
            var item_value = $(this).val();
            if( $(this).is(":checked") ){
                item_list.push( $(this).val() );
            }else{
                var item_index = item_list.indexOf(item_value);
                if(item_index !== -1){
                    item_list.splice(item_index, 1);
                }
            }
            $('#generateBuyerPdfBaseOnItem').val( JSON.stringify(item_list) );
        });

        $(document).on('click', '#sold_item_all', function(event){
            // console.log('sold_item_all ', $(this));
            sold_item_selected_all = 'N';
            if($(this).is(":checked")){
                sold_item_selected_all = 'Y';
            }
            checkSoldItemSelectAll(sold_item_selected_all);
        });

    });

    // function fnCallbackSeller(init=false){
    //     // var old_val = $('#seller').val();
    //     $('#seller').val('');
    //     $('#seller').select2({allowClear:true}).empty();
    //     $('#seller').select2({data:select2customers});
    //     $('#seller').select2();
    // }

    // function fnCallbackBuyer(init=false){
    //     // var old_val = $('#buyer').val();
    //     $('#buyer').val('');
    //     $('#buyer').select2({allowClear:true}).empty();
    //     $('#buyer').select2({data:select2customers});
    //     $('#buyer').select2();
    // }

    function filterItem(sort_type, sort_by, page=null)
    {
        var url = "/manage/marketplaces/sold_item_filter";
        if(page != null){
            url = "/manage/marketplaces/sold_item_filter?page="+page;
        }
        $.ajax({
            url:url,
            type: 'post',
            data: $('#itemFilterForm').serialize()+"&_token="+_token+"&sort_by="+sort_by+"&sort_type="+sort_type,
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == 'success'){
                    // $('#divItemList').html(response.html);
                    $('table > tbody').html(response.html);
                    checkSoldItemSelectAll("N");
                    $("img").bind("error",function(){
                        $(this).attr("src", "{{ asset('images/default.jpg') }}");
                    });
                }
            }
        });
    }

    function checkSoldItemSelectAll(sold_item_selected_all)
    {
        // console.log('sold_item_selected_all ', sold_item_selected_all);
        var item_list = [];
        if(sold_item_selected_all == 'Y'){
            $('#sold_item_all').prop('checked', true);
            $('.item_id').each(function(index){
                $(this).prop('checked', true);
                item_list.push( $(this).val() );
            });
        }
        if(sold_item_selected_all == 'N'){
            $('#sold_item_all').prop('checked',false);
            $('.item_id').each(function(index){
                $(this).prop('checked', false);
            });
            item_list = [];
        }
        // console.log('item_list ', item_list);
        $('#generateBuyerPdfBaseOnItem').val( JSON.stringify(item_list) );
    }

    function generateDispatch(type)
    {
        var customer_id = $('#'+type).val();
        if (customer_id) {
            var item_ids = $('#generateBuyerPdfBaseOnItem').val();
            if(item_ids == "[]"){
                bootbox.alert('At least check one item in item list');
            }
            if(item_ids != "[]"){
                bootbox.prompt({
                    size: "medium",
                    title: "Additional Notes",
                    className: 'large',
                    inputType: 'textarea',
                    buttons: {
                        cancel: {
                            label: 'Cancel',
                            className: 'btn-danger'
                        },
                        confirm: {
                            label: 'Continue',
                            className: 'btn-success'
                        }
                    },
                    callback: function(result){
                         if ($.trim(result) != '') {
                            var action = "/manage/customers/"+customer_id+"/genereate-saleroom-dispatch";
                            $('#genreateDispatchItems').val(item_ids);
                            $('#genreateDispatchNotes').val(result);
                            $('#generateDispatchForm').attr('action', action);
                            $('#generateDispatchForm').submit();
                         }
                        /* result = String containing user input if OK clicked or null if Cancel clicked */
                    }
                });
            }
        }else{
             bootbox.alert('Unknown customer for dispatch !');
        }
    }

    var pageSize = 10;
    function customerSelect2(obj) {
        console.log('customerSelect2 for ',obj);
        var defaultTxtOnInit = 'a';
        $(obj).select2({
            // allowClear: true,
            ajax: {
                url: "/manage/customers/select2_all_customer",
                dataType: 'json',
                delay: 250,
                global: false,
                data: function (params) {
                    params.page = params.page || 1;
                    return {
                        search: params.term ? params.term : defaultTxtOnInit,
                        pageSize: pageSize,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.result,
                        pagination: {
                            more: (params.page * pageSize) < data.counts
                        }
                    };
                },
                cache: true
            },
            placeholder: {
                id: '0', // the value of the option
                text: 'All'
            },
            width: '100%',
            //minimumInputLength: 3,
        });
    }

</script>

@stop
