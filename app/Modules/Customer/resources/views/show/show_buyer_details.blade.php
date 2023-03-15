<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('GST Registered?') }}</label>
        <div class="input-group">
            <label class="radio-inline" for="buyer_gst_registered_true">
                {{ Form::radio('buyer_gst_registered', 'Y', ($customer->buyer_gst_registered == '1')?true:false, ['id' => "buyer_gst_registered_true", 'disabled']) }}
                Yes
                &nbsp;
            </label>
            <label class="radio-inline" for="buyer_gst_registered_false">
                {{ Form::radio('buyer_gst_registered', 'N', ($customer->buyer_gst_registered == '0')?true:false, ['id' => "buyer_gst_registered_false", 'disabled']) }}
                No
                &nbsp;
            </label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="form-control-label">{{ __('Note To Appear On Invoice') }}</label>
        <div class="form-group">
            {{ Form::textarea('note_to_appear_on_invoice', $customer->note_to_appear_on_invoice, [
                    'class' => 'form-control form-control-md',
                    'rows' => 3,
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>
<div class="row">&nbsp;</div>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <h5><strong>{{ __('Item List') }}</strong></h5>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-2 col-xl-2">
        <label class="form-control-label">{{ __('Per page') }}</label>
        {{ Form::select('purchased_item_per_page', ['10'=>'10', '20'=>'20', '50'=>'50'], null, [
                'class'=>'form-control', 'id'=>'purchased_item_per_page'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Filter by Status') }}</label>
        {{ Form::select('filter_buyer_status', $sold_item_statuses, null, [
                'class'=>'form-control', 'id'=>'filter_buyer_status'
            ])
        }}
    </div>
     <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">&nbsp;</label>
        <div class="form-group">
            <form action="{{ route('customer.customers.generateSaleroomDispatch', $customer) }}" id="generatePurchaseSaleRoomDispatchPdfForm" method="post" target="_blank">
                @csrf
                <input type="hidden" name="items" id="generatePurchaseSaleRoomDispatchBaseOnItem">
                <input type="hidden" name="additional_note" id="generatePurchaseSaleRoomDispatchAdditionalNote">
                <button type="button" class="btn btn-outline-danger float-right mb-3" id="generatePurchaseSaleRoomDispatchPdf">{{ __('Generate Dispatch') }}</button>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12" id="divPurchasedItemList">
    </div>
    <input type="hidden" name="purchased_item_page" id="purchased_item_page" value="1" />
</div>

<div class="row">&nbsp;</div>

<div class="row">
    <div class="col-md-12">
        <div id="invoice_list_view"></div>
    </div>
</div>

@section('scripts')
@parent

<script type="text/javascript">
$(function() {
    getPurchasedItems(10);
    getInvoiceList();

    $(document).on('click', '.purchased_item_id', function(){
        // console.log('purchased_item_id ',$(this).is(":checked"));
        var item_value = $(this).val();
        if( $(this).is(":checked") ){
            purchased_item_list.push( $(this).val() );
        }else{
            $('#purchased_item_all').prop('checked', false);
            var item_index = purchased_item_list.indexOf(item_value);
            if(item_index !== -1){
                purchased_item_list.splice(item_index, 1);
            }
        }
        $('#generatePurchaseSaleRoomDispatchBaseOnItem').val( JSON.stringify(purchased_item_list) );
    });

    $(document).on('click', '#purchased_item .pagination a', function(event){
        // console.log('purchased_item .pagination ',$(this));
        event.preventDefault();
        var per_page = $('#purchased_item_per_page').val();
        var page = $(this).attr('href').split('page=')[1];
        $('#purchased_item_page').val(page);
        // console.log('purchased_item_page : ',page);

        $('#purchased_item .pagination li').removeClass('active');
        $(this).parent().addClass('active');
        getPurchasedItems(per_page, page);
    });

    $('#purchased_item_per_page').change(function(){
        var per_page = $(this).val();
        // var page = $('#purchased_item_page').val();
        getPurchasedItems(per_page);
    });

    $('#filter_buyer_status').change(function(){
        filter_buyer_status = $(this).val();
        var per_page = $('#purchased_item_per_page').val();
        getPurchasedItems(per_page);
    });

    $(document).on('click', '#purchased_item_all', function(event){
        // console.log('click #purchased_item_all ', $(this));
        purchased_item_selected_all = 'N';
        if($(this).is(":checked")){
            purchased_item_selected_all = 'Y';
        }
        checkPurchaseItemSelectAll(purchased_item_selected_all);
    });

    $('#generatePurchaseSaleRoomDispatchPdf').on('click', function () {
        var item_ids = $('#generatePurchaseSaleRoomDispatchBaseOnItem').val();
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
                        $('#generatePurchaseSaleRoomDispatchAdditionalNote').val(result);
                        $('#generatePurchaseSaleRoomDispatchPdfForm').submit();
                     }
                    /* result = String containing user input if OK clicked or null if Cancel clicked */
                }
            });
        }
    });
});

function chargeWithAmount(payment, invoice_id, amount) {
    var customer_id = {!! $customer->id !!}
    var payload = JSON.parse('{ "amount": '+amount+', "payment": '+JSON.stringify(payment)+', "customer_id": '+customer_id+', "invoice_id": '+invoice_id+' }');
    fetch('/stripe/charges', {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest"
            },
        method: 'post',
        credentials: "same-origin",
        body: JSON.stringify(payload)
    })
    .then((response) => {
        return response.json();
    })
    .then(function (response) {
        if(response.success == true){
            window.location.reload();
        }
    })
    .catch(function(error) {
    });
}

function declinePayment(invoice_id) {
    alert(invoice_id);

}

function declineInvoice(invoice_id) {
    var response = confirm("Are you sure to decline this Invoice?");
    if (response == true) {
        $.ajax({
            url: "/manage/customers/decline_invoice",
            type: 'post',
            data: "invoice_id=" + invoice_id + "&_token=" + _token,
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == '1') {
                    location.reload();
                }else {
                    alert(response.message);
                    return false;
                }
            }
        });
    }
}

function getInvoiceList(timesRun = true) {
    if (timesRun) {
        progressBar();
    }
    $.ajax({
        url: '/manage/customers/'+customer_id+'/getInvoiceList',
        type: 'get',
        data: {},
        dataType: 'json',
        async: false,
        success: function(response) {
            if(response.status == 'success'){
                $('#invoice_list_view').html(response.html);
            }
        }
    });
}

function checkPurchaseItemSelectAll(purchased_item_selected_all)
{
    // console.log('purchased_item_selected_all ', purchased_item_selected_all);
    if(purchased_item_selected_all == 'Y'){
        $('#purchased_item_all').prop('checked', true);
        $('.purchased_item_id').each(function(index){
            $(this).prop('checked', true);
            purchased_item_list.push( $(this).val() );
        });
    }
    if(purchased_item_selected_all == 'N'){
        $('#purchased_item_all').prop('checked', false);
        $('.purchased_item_id').each(function(index){
            $(this).prop('checked', false);
        });
        purchased_item_list = [];
    }
    // console.log('purchased_item_list ', purchased_item_list);
    $('#generatePurchaseSaleRoomDispatchBaseOnItem').val( JSON.stringify(purchased_item_list) );
}

function getPurchasedItems(per_page, page)
{
    if(stepBuyerDetail == 1){
        progressBar();
    }
    var url = "/manage/customers/"+customer_id+"/purchased_item";
    if(page != null){
        url = "/manage/customers/"+customer_id+"/purchased_item?page="+page;
    }

    $.ajax({
        url:url,
        type: 'post',
        data: "_token="+_token+"&per_page="+per_page+"&filter_buyer_status="+filter_buyer_status,
        dataType: 'json',
        async: false,
        success:function(response)
        {
            if(response.status == 'success'){
                $('#divPurchasedItemList').html(response.html);
                $("img").bind("error",function(){
                    $(this).attr("src", "{{ asset('images/default.jpg') }}");
                });
            }
        }
    });
}
</script>
@stop