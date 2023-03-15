{!! Form::model($item, ['route'  => ['item.items.save_item_fee_structure', $item], 'id'=>'sellerPackageForm', 'data-parsley-validate'=>'true', 'method' => 'POST', 'autocomplete' => 'off', 'data-parsley-excluded'=>"input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden"]) !!}

<div class="card-block">
    <input type="hidden" name="tab_name" value="fee_structure">

    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Fee Type') }} <span style="color:red;">*</span></label>
        <div class="form-group col-md-9">
            <div class="input-group">
                <label class="radio-inline" for="sales_commission">
                    {{ Form::radio('fee_type', 'sales_commission', ($item->fee_type == 'sales_commission')?true:null, [
                            'class' => 'fee_type',
                            'id' => "sales_commission",
                            'required',
                            'data-parsley-errors-container'=>"#error_fee_type",
                            'v-model' => 'feeType'
                        ])
                    }}
                    Sales Commission
                    &nbsp;&nbsp;
                </label>
                <label class="radio-inline" for="fixed_cost_sales_fee">
                    {{ Form::radio('fee_type', 'fixed_cost_sales_fee', ($item->fee_type == 'fixed_cost_sales_fee')?true:null, [
                            'class' => 'fee_type',
                            'id' => "fixed_cost_sales_fee",
                            'required',
                            'data-parsley-errors-container'=>"#error_fee_type",
                            'v-model' => 'feeType'
                        ])
                    }}
                    Fixed Cost Sales Fee
                    &nbsp;&nbsp;
                </label>
                <label class="radio-inline" for="hotlotz_owned_stock">
                    {{ Form::radio('fee_type', 'hotlotz_owned_stock', ($item->fee_type == 'hotlotz_owned_stock')?true:null, [
                            'class' => 'fee_type',
                            'id' => "hotlotz_owned_stock",
                            'required',
                            'data-parsley-errors-container'=>"#error_fee_type",
                            'v-model' => 'feeType'
                        ])
                    }}
                    Hotlotz Owned Stock
                </label>
            </div>
            <div id="error_fee_type"></div>
        </div>
    </div>

    @if(isset($lifecyclename))
    <div class="form-row">
        <div class="form-group col-12 col-md-6 col-xl-6">
            <label class="form-control-label">{{ __('Lifecycle') }}</label>
            {{ Form::text('lifecycle', $lifecyclename, [
                    'class' => 'form-control',
                    'disabled'
                ])
            }}
        </div>
    </div>

    <div class="form-row">
        @foreach($itemlifecycles as $key => $cycle)            
            @php
                $label = '';
                if($cycle['type'] == 'auction'){
                    $label = ucfirst($cycle['type']).' Opening Bid';
                }
                if($cycle['type'] == 'marketplace'){
                    $label = ucfirst($cycle['type']).' Buy Now Price';
                }
                if($cycle['type'] == 'clearance'){
                    $label = ucfirst($cycle['type']).' Price';
                }
                if($cycle['type'] == 'storage'){
                    $label = ucfirst($cycle['type']).' daily fee';
                }
            @endphp
            <div class="form-group col-12 col-md-6 col-xl-6">
                <label class="form-control-label">{{ $label }}</label>
                {{ Form::text('item_lifecycle', $cycle['price'], [
                        'class' => 'form-control',
                        'disabled'
                    ])
                }}
            </div>
        @endforeach
    </div>
    @endif

    <div class="form-row">
        <div class="col-md-12">
            <label class="form-control-label">{{ __('Internal Comments') }} </label>
            <div class="form-group">
                {{ Form::textarea('internal_note', isset($item->customer)?$item->customer->internal_note : null, [
                        'class' => 'form-control form-control-md',
                        'rows'=>3,
                        'disabled'
                    ])
                }}
            </div>
        </div>
    </div>

    <div class="form-row" id="itemPackage">
        @include('item::itempackage._package_details', [$lifecyclename,  $itemlifecycles])
    </div>
</div>

<div class="card-footer">
    @if( $item->permission_to_sell != 'Y' )
        <button class="btn btn-outline-success">{{ __('Save') }}</button>
    @endif
    <a href="{{ route('item.items.show',['item'=>$item])}}" class="btn btn-outline-danger">{{ __('Cancel') }}</a>
</div>

{!! Form::close() !!}

@include('item::customer_modal')