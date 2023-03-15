@extends('appshell::layouts.default')

@section('styles')
@stop


@section('title')
    {{ __('Items') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
            @can('create items')
            <div class="card-actionbar">
                <a href="{{ route('item.items.add_item_from_item') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Item') }}
                </a>
            </div>
            @endcan
        </div>

        <div class="card-block">
            @include('item::filter_item')
        </div>

        <div class="card-block" id="divItemList">
            @include( 'item::_index_table' )
        </div>
        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    </div>

    @include('item::loading_modal');

@stop


@section('scripts')
<!-- ### Additional CSS ### -->
<link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" />
<script src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<!-- Select2 CSS -->
<link href="{{asset('plugins\select2-develop\dist\css\select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins\select2-bootstrap4-theme-master\dist\select2-bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Select2 JS -->
<script src="{{asset('plugins\select2-develop\dist\js\select2.full.min.js')}}"></script>

<!-- Bootstrap Multiselect with Checkbox -->
<script src="{{asset('plugins\bootstrap-multiselect-dropdown\js\bootstrap-multiselect.js')}}"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script> -->
<link rel="stylesheet" href="{{asset('plugins\bootstrap-multiselect-dropdown\css\bootstrap-multiselect.css')}}">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css"> -->

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $(function(){

        $("#category").multiselect({
            includeSelectAllOption:true,
            includeSelectAllIfMoreThan: 0,
            selectAllText:' Select all',
            selectAllValue:'multiselect-all',
            selectAllName:false,
            selectAllNumber:true,
            // enableFiltering:true,
            buttonWidth: '100%',
        });

        $("#action_required").multiselect({
            includeSelectAllOption:true,
            includeSelectAllIfMoreThan: 0,
            selectAllText:' Select all',
            selectAllValue:'multiselect-all',
            selectAllName:false,
            selectAllNumber:true,
            // enableFiltering:true,
            buttonWidth: '100%',
        });

        $("#item_status").multiselect({
            includeSelectAllOption:true,
            includeSelectAllIfMoreThan: 0,
            selectAllText:' Select all',
            selectAllValue:'multiselect-all',
            selectAllName:false,
            selectAllNumber:true,
            // enableFiltering:true,
            buttonWidth: '100%',
        });

        // $.fn.select2.defaults.set("placeholder", 'All');
        // fnCallbackSeller(false);
        customerSelect2();

        $('.marketplace, #btnSearch').click(function(){
            filterItem();
        });

        $('#search_text').keypress(function(event){
            var keyCode = (event.keyCode ? event.keyCode : event.which);
            if (keyCode == 13) {
                $('#btnSearch').trigger('click');
                return false;
            }
        });

        $('#lifecycle, #permission_to_sell, #auction, #category, #action_required, #item_status, #seller, #tag, #per_page').change(function(){
            filterItem();
        });

        $('#btnResetAll').click(function(){
            location.reload();
        });

        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page').val(page);

            $('li').removeClass('active');
            $(this).parent().addClass('active');
            filterItem(page);
        });

        $(document).on('click', '#btnDuplicateConfirm', function(){
            var item_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to duplicate '+name+'?';

            var response = confirm(content);
            if (response == true) {
                // bootbox.dialog({ message: 'Processing ... Please Wait ...', closeButton: false });
                $.ajax({
                    url: '/manage/items/'+item_id+'/duplicate',
                    type: 'post',
                    data: {
                        "id": item_id,
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
        });

        $(document).on('click', '#btnDeleteConfirm', function(){
            var item_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to delete '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/items/'+item_id,
                    type: 'delete',
                    data: {
                        "id": item_id,
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
        });

        //## Autocomplete for SearchBox

        $( "#search_text" ).autocomplete({
             delay: 3000,
             minLength: 5,
             source:function(request,response){
                    $.get("/manage/items/search",{'name':$( "#search_text" ).val()}).done(function(data, status){
                    response(JSON.parse(data));
                });
              }
        });
    });

    // function fnCallbackSeller(init=false){
    //     // var old_val = $('#seller').val();
    //     $('#seller').val('');
    //     $('#seller').select2({allowClear:true}).empty();
    //     $('#seller').select2({data:select2customers});
    //     $('#seller').select2();
    // }

    function filterItem(page=null)
    {
        var url = "/manage/items/filter";
        if(page != null){
            url = "/manage/items/filter?page="+page;
        }
        $.ajax({
            url:url,
            type: 'post',
            data: $('#itemFilterForm').serialize()+"&_token="+_token,
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == 'success'){
                    $('#divItemList').html(response.html);
                    $("img").bind("error",function(){
                        $(this).attr("src", "{{ asset('images/default.jpg') }}");
                    });
                }
            }
        });
    }

    var pageSize = 10;
    function customerSelect2() {
        console.log('customerSelect2');
        var defaultTxtOnInit = 'a';
        $('#seller').select2({
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