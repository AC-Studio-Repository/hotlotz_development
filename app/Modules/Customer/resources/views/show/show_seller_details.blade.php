@php
    use \App\Modules\Item\Models\Item;
@endphp

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('GST Registered?') }}</label>
        <div class="input-group">
            <label class="radio-inline" for="buyer_gst_registered_true">
                {{ Form::radio('seller_gst_registered', 'Y', ($customer->seller_gst_registered == '1')?true:false, ['id' => "seller_gst_registered_true", 'disabled']) }}
                Yes
                &nbsp;
            </label>
            <label class="radio-inline" for="seller_gst_registered_false">
                {{ Form::radio('seller_gst_registered', 'N', ($customer->seller_gst_registered == '0')?true:false, ['id' => "seller_gst_registered_false", 'disabled']) }}
                No
                &nbsp;
            </label>
        </div>
    </div>

    @if($customer->seller_gst_registered == '1')
    <div class="col-md-4">
        <label class="form-control-label">{{ __('GST Number') }}</label>
        <div class="form-group">
            {{ Form::text('gst_number', $customer->gst_number, [
                    'class' => 'form-control form-control-md',
                    'placeholder' => __('e.g 12345'),
                    'disabled'
                ])
            }}
        </div>
    </div>
    @endif

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Singapore UEN Number') }}</label>
        <div class="form-group">
            {{ Form::text('sg_uen_number', $customer->sg_uen_number, [
                    'class' => 'form-control form-control-md',
                    'placeholder' => __('e.g 12345'),
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="form-control-label">{{ __('Notes To Appear On Statement') }}</label>
        <div class="form-group">
            {{ Form::textarea('note_to_appear_on_statement', $customer->note_to_appear_on_statement, [
                    'class' => 'form-control form-control-md',
                    'rows'=>3,
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>

<div class="divSellItem" v-show="formType == 'edit'">
    <div class="row">
        <div class="form-group col-12 col-md-12 col-xl-12">
            <h5><strong>{{ __('Item List') }}</strong></h5>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-12 col-md-2 col-xl-2">
            <label class="form-control-label">{{ __('Per page') }}</label>
            {{ Form::select('sell_item_per_page', ['10'=>'10', '20'=>'20', '50'=>'50', 'all' => 'All'], null, [
                    'class'=>'form-control', 'id'=>'sell_item_per_page'
                ])
            }}
        </div>
        <div class="form-group col-12 col-md-2 col-xl-2">
            <label class="form-control-label">{{ __('Filter by Status') }}</label>
            {{ Form::select('filter_seller_status', $sale_item_statuses, null, [
                    'class'=>'form-control', 'id'=>'filter_seller_status'
                ])
            }}
        </div>
        <div class="col-12 col-md-8 col-xl-8">
            <label class="form-control-label">&nbsp;</label>
            <div class="form-group">
                <form action="{{ route('customer.customers.generateSaleroomReceipt', $customer) }}" id="generateSaleRoomPdfForm" method="post" target="_blank">
                    @csrf
                    <input type="hidden" name="items" id="generateSaleRoomBaseOnItem">
                    <input type="hidden" name="additional_note" id="generateSaleRoomAdditionalNote">
                    <button type="button" class="btn btn-outline-success float-right mb-3" id="generateSaleRoomPdf">{{ __('Generate Saleroom Receipt') }}</button>
                </form>
                <form action="{{ route('customer.customers.generateSaleroomDispatch', $customer) }}" id="generateSaleRoomDispatchPdfForm" method="post" target="_blank">
                    @csrf
                    <input type="hidden" name="items" id="generateSaleRoomDispatchBaseOnItem">
                    <input type="hidden" name="additional_note" id="generateSaleRoomDispatchAdditionalNote">
                    <button type="button" class="btn btn-outline-danger float-right mb-3" id="generateSaleRoomDispatchPdf">{{ __('Generate Dispatch') }}</button>
                </form>
                {{-- Disable as Request by Client (Jack). Confirm at July 12, 2021 --}}
                {{-- <form action="{{ route('customer.customers.sendSaleroomReceipt', $customer) }}" method="post"> --}}
                {{-- @csrf --}}
                {{-- <button type="submit" class="btn btn-outline-warning float-right mb-3">{{ __('Send Saleroom Receipt') }}</button> --}}
                {{-- </form> --}}

                {{-- <form action="{{ route('customer.customers.generateSellerReport', $customer) }}" id="generateSellerReportPdfForm" method="post" target="_blank">
                    @csrf
                    <input type="hidden" name="items" id="generateSellerReportBaseOnItem">
                    <button type="submit" class="btn btn-outline-warning float-right mb-3" id="generateSellerReportPdf">{{ __('Generate Seller Report') }}</button>
                </form> --}}
                <div class="btn-group float-right ml-3">
                    <button type="button" class="btn btn-outline-success dropdown-toggle mb-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Generate Seller Report
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" onclick="download_table_as_csv('sell_items_table');" href="#">CSV</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-12 col-md-12 col-xl-12" id="divSellItemList">
        </div>
        <input type="hidden" name="sell_item_page" id="sell_item_page" value="1" />
    </div>
    <div class="row">
        <div class="col-12">
        @if( $no_permission_items > 0 )
            <a href="{{ route('customer.customers.request_for_permission', $customer) }}" class="btn btn-outline-primary">{{ __('Request for Permission')}}</a>
        @endif
        </div>
    </div>
</div>
<div class="row">&nbsp;</div>

<div class="row">
    <div class="col-12 col-md-12 col-xl-12">
        <div id="settlement_list_view"></div>
    </div>
</div>

@section('scripts')
@parent

<script type="text/javascript">
$(function() {
    getSellItems(10);
    getSettlementList();

    $(document).on('click', '#btnItemDuplicateConfirm', function(){
        var item_id = $(this).attr('data-id');
        var name = $(this).attr('data-name');
        var content = 'Are you sure to duplicate '+name+'?';

        var response = confirm(content);
        if (response == true) {
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

    $(document).on('click', '#btnItemDeleteConfirm', function(){
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

    $(document).on('click', '.sell_item_id', function(){
        // console.log('sell_item_id ',$(this).is(":checked"));
        var item_value = $(this).val();
        if( $(this).is(":checked") ){
            sell_item_list.push( $(this).val() );
        }else{
            $('#sell_item_all').prop('checked', false);
            var item_index = sell_item_list.indexOf(item_value);
            if(item_index !== -1){
                sell_item_list.splice(item_index, 1);
            }
        }
        $('#generateSaleRoomBaseOnItem').val( JSON.stringify(sell_item_list) );
        $('#generateSaleRoomDispatchBaseOnItem').val( JSON.stringify(sell_item_list) );
        $('#generateSellerReportBaseOnItem').val( JSON.stringify(sell_item_list) );
    });

    $('#generateSaleRoomPdf').on('click', function () {
        var item_ids = $('#generateSaleRoomBaseOnItem').val();
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
                        $('#generateSaleRoomAdditionalNote').val(result);
                        $('#generateSaleRoomPdfForm').submit();
                     }
                    /* result = String containing user input if OK clicked or null if Cancel clicked */
                }
            });
        }
    });

    $('#generateSaleRoomDispatchPdf').on('click', function () {
        var item_ids = $('#generateSaleRoomDispatchBaseOnItem').val();
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
                        $('#generateSaleRoomDispatchAdditionalNote').val(result);
                        $('#generateSaleRoomDispatchPdfForm').submit();
                     }
                    /* result = String containing user input if OK clicked or null if Cancel clicked */
                }
            });
        }
    });

    // $('#generateSellerReportPdf').on('click', function () {
    //     var item_ids = $('#generateSellerReportBaseOnItem').val();
    //     if(item_ids == "[]"){
    //         bootbox.alert('At least check one item in item list');
    //     }
    //     if(item_ids != "[]"){
    //         $('#generateSellerReportPdfForm').submit();
    //     }
    // });

    $(document).on('click', '#sell_item .pagination a', function(event){
        // console.log('sell_item .pagination ',$(this));
        event.preventDefault();
        var per_page = $('#sell_item_per_page').val();
        var page = $(this).attr('href').split('page=')[1];
        $('#sell_item_page').val(page);
        // console.log('sell_item_page : ',page);

        $('#sell_item .pagination li').removeClass('active');
        $(this).parent().addClass('active');
        getSellItems(per_page, page);
    });

    $('#sell_item_per_page').change(function(){
        var per_page = $(this).val();
        // var page = $('#sell_item_page').val();
        getSellItems(per_page);
    });

    $('#filter_seller_status').change(function(){
        filter_seller_status = $(this).val();
        var per_page = $('#sell_item_per_page').val();
        getSellItems(per_page);
    });

    $(document).on('click', '#sell_item_all', function(event){
        // console.log('click #sell_item_all ', $(this));
        sell_item_selected_all = 'N';
        if($(this).is(":checked")){
            sell_item_selected_all = 'Y';
        }
        checkSellItemSelectAll(sell_item_selected_all);
    });
});

function getSellItems(per_page, page)
{
    if (stepSellerDetail == 1) {
        progressBar();
    }
    sell_item_selected_all = 'N';
    if( $('#sell_item_all').is(":checked") ){
        sell_item_selected_all = 'Y';
    }
    var url = "/manage/customers/"+customer_id+"/sell_item";
    if(page != null){
        url = "/manage/customers/"+customer_id+"/sell_item?page="+page;
    }

    $.ajax({
        url:url,
        type: 'post',
        data: "_token="+_token+"&per_page="+per_page+"&filter_seller_status="+filter_seller_status,
        dataType: 'json',
        async: false,
        success:function(response)
        {
            if(response.status == 'success'){
                $('#divSellItemList').html(response.html);
                // var sell_item_selected_all = response.sell_item_selected_all;
                checkSellItemSelectAll('N');
                $("img").bind("error",function(){
                    $(this).attr("src", "{{ asset('images/default.jpg') }}");
                });
            }
        }
    });
}

function getSettlementList(timesRun = true) {
    if (timesRun) {
        progressBar();
    }
    $.ajax({
        url: '/manage/customers/'+customer_id+'/getSettlementList',
        type: 'get',
        data: {},
        dataType: 'json',
        async: false,
        success: function(response) {
            if(response.status == 'success'){
                $('#settlement_list_view').html(response.html);
            }
        }
    });
}

function checkSellItemSelectAll(sell_item_selected_all)
{
    // console.log('sell_item_selected_all ', sell_item_selected_all);
    if(sell_item_selected_all == 'Y'){
        $('#sell_item_all').prop('checked', true);
        $('.sell_item_id').each(function(index){
            $(this).prop('checked', true);
            sell_item_list.push( $(this).val() );
        });
    }
    if(sell_item_selected_all == 'N'){
        $('#sell_item_all').prop('checked', false);
        $('.sell_item_id').each(function(index){
            $(this).prop('checked', false);
        });
        sell_item_list = [];
    }
    // console.log('sell_item_list ', sell_item_list);
    $('#generateSaleRoomBaseOnItem').val( JSON.stringify(sell_item_list) );
    $('#generateSaleRoomDispatchBaseOnItem').val( JSON.stringify(sell_item_list) );
    $('#generateSellerReportBaseOnItem').val( JSON.stringify(sell_item_list) );
}

// Quick and simple export target #table_id into a csv
function download_table_as_csv(table_id, separator = ',') {
    // Select rows from table_id
    var rows = document.querySelectorAll('table#' + table_id + ' tr');
    // Construct csv
    var csv = [];
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll('td, th');
        for (var j = 0; j < cols.length; j++) {
            // Clean innertext to remove multiple spaces and jumpline (break csv)
            if(j != 0 && j != 1 && j != 8){
                var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
                // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
                data = data.replace(/"/g, '""');
                // Push escaped string
                row.push('"' + data + '"');
            }
        }
        csv.push(row.join(separator));
    }
    var csv_string = csv.join('\n');
    // Download it
    var filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';
    var link = document.createElement('a');
    link.style.display = 'none';
    link.setAttribute('target', '_blank');
    link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

</script>
@stop