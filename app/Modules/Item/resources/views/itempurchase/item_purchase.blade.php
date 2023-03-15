{!! Form::model($item, ['route'  => ['item.items.item_purchase', $item], 'id'=>'purchaseDetailForm', 'data-parsley-validate'=>'true', 'method' => 'POST', 'autocomplete' => 'off']) !!}

<div class="card-block">
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Buyer Number') }}</label>
        <div class="form-group col-md-6">
            @if(isset($item->buyer) && $item->buyer_id>0)
                <a class="btn btn-md btn-success" style="width:100%;" href="{{ route('customer.customers.show', $item->buyer) }}" target="_blank">{{ $item->buyer->ref_no }}</a>
            @endif
        </div>
    </div>
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Buyer Name') }}</label>
        <div class="form-group col-md-6">
            @if(isset($item->buyer) && $item->buyer_id>0)
                <a class="btn btn-md btn-success" style="width:100%;" href="{{ route('customer.customers.show', $item->buyer) }}" target="_blank">{{ $item->buyer->fullname }}</a>
            @endif
        </div>
    </div>
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Tel Number') }}</label>
        <div class="form-group col-md-6">
            {{ Form::text('buyer_tel_no', isset($item_purchase['buyer_tel_no'])?$item_purchase['buyer_tel_no']:null, array('class'=>'form-control', 'disabled'=>'true'))}}
        </div>
    </div>
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Email Address') }}</label>
        <div class="form-group col-md-6">
            {{ Form::text('buyer_email', isset($item_purchase['buyer_email'])?$item_purchase['buyer_email']:null, array('class'=>'form-control', 'disabled'=>'true'))}}
        </div>
    </div>
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Address') }}</label>
        <div class="form-group col-md-6">
            {{ Form::textarea('buyer_address', isset($item_purchase['buyer_address'])?$item_purchase['buyer_address']:null, array('class'=>'form-control', 'rows' => 3, 'disabled'=>'true'))}}
        </div>
    </div>
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Town/City') }}</label>
        <div class="form-group col-md-6">
            {{ Form::text('buyer_city', isset($item_purchase['buyer_city'])?$item_purchase['buyer_city']:null, array('class'=>'form-control', 'disabled'=>'true'))}}
        </div>
    </div>
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('County') }}</label>
        <div class="form-group col-md-6">
            {{ Form::text('buyer_county', isset($item_purchase['buyer_county'])?$item_purchase['buyer_county']:null, array('class'=>'form-control', 'disabled'=>'true'))}}
        </div>
    </div>
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Postal Code') }}</label>
        <div class="form-group col-md-6">
            {{ Form::text('buyer_postcode', isset($item_purchase['buyer_postcode'])?$item_purchase['buyer_postcode']:null, array('class'=>'form-control', 'disabled'=>'true'))}}
        </div>
    </div>        
    
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Auction or Marketplace') }}</label>
        <div class="form-group col-md-6">
            {{ Form::text('auction_or_marketplace', isset($item_purchase['auction_or_marketplace'])?$item_purchase['auction_or_marketplace']:null, array('class'=>'form-control', 'disabled'=>'true'))}}
        </div>
    </div>
    
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Invoice/Receipt Link') }}</label>
        <div class="form-group col-md-6">
            <a href="{{ $invoice_url }}" class="btn btn-link text-muted">Link</a>
        </div>
    </div>
    
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Settlement Link') }}</label>
        <div class="form-group col-md-6">
            <a href="{{ $bill_url }}" class="btn btn-link text-muted">Link</a>
        </div>
    </div>
    
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Set Collection/Delivery Status') }}</label>
        <div class="form-group col-md-6">
            {{ Form::text('collection_delivery_status', isset($item_purchase['collection_delivery_status'])?$item_purchase['collection_delivery_status']:null, array('class'=>'form-control', 'disabled'=>'true'))}}
        </div>
    </div>
</div>

<!-- <div class="card-footer">
    <button class="btn btn-success" id="btnItemPurchase">{{ __('Save') }}</button>
    <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
</div> -->

{!! Form::close() !!}