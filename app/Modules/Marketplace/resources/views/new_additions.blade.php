@extends('appshell::layouts.default')

@section('title')
    {{ __('New Additions') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        <div class="card-block">
            @include('marketplace::new_addition_filter')
        </div>

        <div class="card-block" id="divItemList">
            @include('marketplace::new_addition_table')
        </div>

        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    </div>

@stop

@section('scripts')

<!-- Select2 CSS -->
<link href="{{asset('plugins/select2-develop/dist/css/select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/select2-bootstrap4-theme-master/dist/select2-bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Select2 JS -->
<script src="{{asset('plugins/select2-develop/dist/js/select2.full.min.js')}}"></script>

<script type="text/javascript">
    
    var _token = $('input[name="_token"]').val();
    var item_list = [];
    var new_addition_selected_all = {!! json_encode($new_addition_selected_all) !!};

    $(function(){

        checkNewAdditionSelectAll(new_addition_selected_all);

        $('#btnResetAll').click(function(){
            location.reload();
        });

        // $.fn.select2.defaults.set("placeholder", 'All');
        // fnCallbackSeller(false);
        customerSelect2();

        $('#seller, #new_addition_status, #per_page').change(function(){
            filterItem();
        });

        $('#btnSearch').click(function(){
            filterItem();
        });

        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#hidden_page').val(page);

            $('li').removeClass('active');
            $(this).parent().addClass('active');
            filterItem(page);
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
            $('#generatePdfBaseOnItem').val( JSON.stringify(item_list) );
        });

        $(document).on('click', '#new_addition_all', function(event){
            // console.log('new_addition_all ', $(this));
            new_addition_selected_all = 'N';
            if($(this).is(":checked")){
                new_addition_selected_all = 'Y';
            }
            checkNewAdditionSelectAll(new_addition_selected_all);
        });

        //Highlight
        $(document).on("click", ".switch-input", function() {
            document.querySelectorAll('input.switch-input').forEach(elem => {
                elem.disabled = true;
            });
            var highlight_item_id = $(this).data('id');

            var status = $(this).prop('checked');

            var is_highlight = 'N';
            if(status == true) {
                is_highlight = 'Y';
            }

            $.ajax({
                url: "/manage/items/"+highlight_item_id+"/set_highlight",
                type: 'post',
                data: {"is_highlight":is_highlight, "_token": '{{ csrf_token() }}'},
                dataType: 'json',
                async: false,
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
        var url = "/manage/marketplaces/new_addition_filter";
        if(page != null){
            url = "/manage/marketplaces/new_addition_filter?page="+page;
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
                    checkNewAdditionSelectAll("N");
                    $("img").bind("error",function(){
                        $(this).attr("src", "{{ asset('images/default.jpg') }}");
                    });
                }
            }
        });
    }

    function checkNewAdditionSelectAll(new_addition_selected_all)
    {            
        // console.log('new_addition_selected_all ', new_addition_selected_all);
        var item_list = [];
        if(new_addition_selected_all == 'Y'){
            $('#new_addition_all').prop('checked', true);
            $('.item_id').each(function(index){
                $(this).prop('checked', true);
                item_list.push( $(this).val() );
            });
        }
        if(new_addition_selected_all == 'N'){
            $('#new_addition_all').prop('checked', false);
            $('.item_id').each(function(index){
                $(this).prop('checked', false);
            });
            item_list = [];
        }
        // console.log('item_list ', item_list);
        $('#generatePdfBaseOnItem').val( JSON.stringify(item_list) );
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
