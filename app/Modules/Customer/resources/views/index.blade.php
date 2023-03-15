@extends('appshell::layouts.default')

@section('styles')
@stop

@section('title')
    {{ __('Clients') }}
@stop

@section('content')
    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create customers')
                <a href="{{ route('customer.customers.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Client') }}
                </a>
                @endcan
            </div>
        </div>

        <div class="card-block">
            @include('customer::customer_filter')
        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="10%">{{ __('Photo') }}</th>
                            <th width="10%" class="sorting" data-sorting_type="asc" data-column_name="fullname" style="cursor: pointer">{{ __('Name') }} <span id="fullname_icon"></span></th>
                            <th width="10%">{{ __('Contact Details') }}</th>
                            <th width="10%">{{ __('MCC') }}</th>
                            <th width="10%">{{ __('Statistics') }}</th>
                            <th width="7%">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @include('customer::_pagination')
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
<!-- ### Additional CSS ### -->
<link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" />
<script src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

<!-- ### Additional JS ### -->
<script src="{{ asset('fontawesome/js/all.min.js') }}"></script>

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script>
    var _token = $('input[name="_token"]').val();

    $(function(){

        function clear_icon()
        {
            $('#ref_no_icon').html('');
            $('#fullname_icon').html('');
            $('#company_name_icon').html('');
        }

        function fetch_data(sort_type, sort_by, page=null)
        {
            var url = "/manage/customers/pagination/fetch_data";
            if(page != null){
                url = "/manage/customers/pagination/fetch_data?page="+page;
            }

            $.ajax({
                url:url,
                type: 'post',
                data: $('#customerFilterForm').serialize()+"&_token="+_token+"&sort_by="+sort_by+"&sort_type="+sort_type,
                dataType: 'json',
                async: false,
                success:function(data)
                {
                    if(data.status = '1'){
                        $('table > tbody').html(data.html);
                    }
                }
            });
        }

        $('#per_page, #country_id, #main_client_contact, #client_status').change(function(){
            var sort_type = $('#hidden_sort_type').val();
            var sort_by = $('#hidden_column_name').val();
            fetch_data(sort_type, sort_by);
        });

        $('#btnSearch').click(function(){
            var sort_type = $('#hidden_sort_type').val();
            var sort_by = $('#hidden_column_name').val();
            fetch_data(sort_type, sort_by);
        });

        $('#btnResetAll').click(function(){
            location.reload();
        });

        $('#search_text').change(function(event){
            var keyCode = (event.keyCode ? event.keyCode : event.which);
            if (keyCode == 13) {
                $('#btnSearch').trigger('click');
                return false;
            }
        });

        $('form').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                $('#btnSearch').trigger('click');
                return false;
            }
        });

        $(document).on('click', '.sorting', function(){
            var column_name = $(this).attr('data-column_name');
            var order_type = $(this).attr('data-sorting_type');
            var reverse_order = '';
            if(order_type == 'asc')
            {
                $(this).attr('data-sorting_type','desc');
                reverse_order = 'desc';
                clear_icon();
                $('#'+column_name+'_icon').html('<i class="fas fa-angle-down"></i>');
            }
            if(order_type == 'desc')
            {
                $(this).attr('data-sorting_type','asc');
                reverse_order = 'asc';
                clear_icon
                $('#'+column_name+'_icon').html('<i class="fas fa-angle-up"></i>');
            }
            $('#hidden_column_name').val(column_name);
            $('#hidden_sort_type').val(reverse_order);
            var page = $('#hidden_page').val();
            fetch_data(reverse_order, column_name, page);
        });

        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page').val(page);
            var column_name = $('#hidden_column_name').val();
            var sort_type = $('#hidden_sort_type').val();

            $('li').removeClass('active');
            $(this).parent().addClass('active');
            fetch_data(sort_type, column_name, page);
        });

        $(document).on('click', '#btnBought, #btnSold', function(){
            customer_id = $(this).attr('data-id');
            customer_tab = $(this).attr('data-customer_tab');

            var url = {!! json_encode(asset("/manage/customers/")) !!};
            url = url + '/' + customer_id;

            $.ajax({
                url: "/manage/customers/check_tab",
                type: 'post',
                data: "customer_tab="+customer_tab+"&_token="+_token,
                dataType: 'json',
                async: false,
                success: function(data) {
                    window.open(url, '_self');
                }
            });
        });

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

        $( "#search_text" ).autocomplete({
             delay: 3000,
             minLength: 2,
             source:function(request,response){
                    $.get("/manage/customers/search",{'name':$( "#search_text" ).val()}).done(function(data, status){
                    response(JSON.parse(data));
                });
              }
        });

    });

</script>
@stop
