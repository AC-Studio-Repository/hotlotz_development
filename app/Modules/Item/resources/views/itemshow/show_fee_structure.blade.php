<div class="card-block">
    <div class="form-row">
        <label class="form-control-label col-md-3">{{ __('Fee Type') }} <span style="color:red;">*</span></label>
        <div class="form-group col-md-9">
            <div class="input-group">
                <label class="radio-inline" for="sales_commission">
                    {{ Form::radio('fee_type', 'sales_commission', ($item->fee_type == 'sales_commission')?true:null, [
                            'id' => "sales_commission",
                            'disabled',
                        ])
                    }}
                    Sales Commission
                    &nbsp;&nbsp;
                </label>
                <label class="radio-inline" for="fixed_cost_sales_fee">
                    {{ Form::radio('fee_type', 'fixed_cost_sales_fee', ($item->fee_type == 'fixed_cost_sales_fee')?true:null, [
                            'id' => "fixed_cost_sales_fee",
                            'disabled',
                        ])
                    }}
                    Fixed Cost Sales Fee
                    &nbsp;&nbsp;
                </label>
                <label class="radio-inline" for="hotlotz_owned_stock">
                    {{ Form::radio('fee_type', 'hotlotz_owned_stock', ($item->fee_type == 'hotlotz_owned_stock')?true:null, [
                            'id' => "hotlotz_owned_stock",
                            'disabled',
                        ])
                    }}
                    Hotlotz Owned Stock
                </label>
            </div>
        </div>
    </div>

    <div class="row">
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
                <label class="form-control-label">
                    {{ $label }}
                </label>

                {{ Form::text('item_lifecycle', number_format($cycle['price']), [
                        'class' => 'form-control',
                        'disabled'
                    ])
                }}
            </div>
        @endforeach
    </div>

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

    <div id="itemPackage">
        @include('item::itemshow.show_package_details', [$item,  $itemlifecycles])
    </div>


    @can('item approve')
        @if($item->fee_type != null)
        <div class="form-row">
            <div class="form-group col-12 col-md-6 col-xl-6">
                <label class="form-control-label">{{ __('Approver') }}</label>
                {{ Form::select('fee_structure_approver_id', $approvers, ($item->fee_structure_approver_id > 0)? (integer)$item->fee_structure_approver_id:$user_id, [
                        'class'=>'form-control',
                        'id' => 'fee_structure_approver_id',
                        (isset($item->fee_structure_approver_id) && $item->fee_structure_approver_id > 0)?'disabled':null,
                    ])
                }}

                @if ($errors->has('fee_structure_approver_id'))
                    <input hidden class="form-control is-invalid">
                    <div class="invalid-feedback">{{ $errors->first('fee_structure_approver_id') }}</div>
                @endif
            </div>
            <div class="form-group col-12 col-md-6 col-xl-6" id="divFeeStructureApproveButton">
                <label class="form-control-label">&nbsp;</label>
                @if( $item->is_fee_structure_approved != 'Y' )
                    <div>
                        <button type="button" class="btn btn-primary" id="btnFeeStructureApprove">{{ __('Approve') }}</button>
                    </div>
                @endif
            </div>
        </div>
        @endif
    @endcan
</div>