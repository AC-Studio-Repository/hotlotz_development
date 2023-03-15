<div class="card-block">
    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Valuer') }}</label>
            {{ Form::select('valuer_id', $valuers, $item->valuer_id, [
                    'class'=>'form-control',
                    'disabled'
                ])
            }}
        </div>

        @can('item approve')
            @if($item->lifecycle_id > 0)
                <div class="form-group col-12 col-md-4 col-xl-4">
                    <label class="form-control-label">{{ __('Approver') }}</label>
                    {{ Form::select('valuation_approver_id', $approvers, ($item->valuation_approver_id > 0)? (integer)$item->valuation_approver_id:$user_id, [
                            'class'=>'form-control',
                            'id' => 'valuation_approver_id',
                            (isset($item->valuation_approver_id) && $item->valuation_approver_id > 0)?'disabled':null,
                        ])
                    }}

                    @if ($errors->has('valuation_approver_id'))
                        <input hidden class="form-control is-invalid">
                        <div class="invalid-feedback">{{ $errors->first('valuation_approver_id') }}</div>
                    @endif
                </div>
                <div class="form-group col-12 col-md-4 col-xl-4" id="divValuationApproveButton">
                    <label class="form-control-label">&nbsp;</label>
                    @if( $item->is_valuation_approved != 'Y' )
                        <div>
                            <button type="button" class="btn btn-primary" id="btnValuationApprove">{{ __('Approve') }}</button>
                        </div>
                    @endif
                </div>
            @endif
        @endcan
    </div>

    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('GST Rate') }}</label>
            {{ Form::text('gst_rate', $gst_rate, [
                    'class' => 'form-control',
                    'disabled'
                ])
            }}
        </div>

        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Low Estimate') }}</label>
            {{ Form::text('low_estimate', number_format($item->low_estimate), [
                    'class' => 'form-control',
                    'disabled'
                ])
            }}
        </div>

        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('High Estimate') }}</label>
            {{ Form::text('high_estimate', number_format($item->high_estimate), [
                    'class' => 'form-control',
                    'disabled'
                ])
            }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Reserve') }}</label>
            <div class="input-group" style="width: 100%">
                <span class="input-group-addon">
                    {{ Form::checkbox('is_reserve', 'Y', ($item->is_reserve == 'Y')?'checked':'', [
                            'id' => "is_reserve",
                            'disabled'
                        ])
                    }}
                </span>
                {{ Form::text('reserve', ($item->reserve != null)?number_format($item->reserve):null, [
                        'class' => 'form-control',
                        'disabled'
                    ])
                }}
            </div>
        </div>

        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Hotlotz Own Stock?') }}</label>
            <div class="input-group">
                <label class="checkbox-inline" for="is_hotlotz_own_stock">
                    {{ Form::checkbox('is_hotlotz_own_stock', 'Y', ($item->is_hotlotz_own_stock == 'Y')?'checked':'', [
                            'id' => "is_hotlotz_own_stock",
                            'disabled'
                        ])
                    }}
                </label>
            </div>
        </div>
    </div>

    @if($item->is_hotlotz_own_stock == 'Y')
    <div class="row divHotlotzOwnStock">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Supplier') }}</label>
            {{ Form::text('supplier', isset($item->supplier)?$item->supplier:null, [
                    'class' => 'form-control',
                    'disabled'
                ])
            }}
        </div>

        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Purchase Cost') }} <span style="color:red">*</span></label>
            {{ Form::text('purchase_cost', ($item->purchase_cost != null)?number_format($item->purchase_cost):null, [
                    'class' => 'form-control',
                    'disabled'
                ])
            }}
        </div>

        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('GST by Supplier') }} <span style="color:red">*</span></label>
            {{ Form::text('supplier_gst', ($item->supplier_gst != null)?$item->supplier_gst:null, [
                    'class' => 'form-control',
                    'disabled'
                ])
            }}
        </div>
    </div>
    @endif

    <div class="row">
        <div class="form-group col-md-12">
            <label class="form-control-label">{{ __('Lifecycle') }}</label>
            {{ Form::text('lifecycle_id', (isset($item->lifecycle_id) && $item->lifecycle_id != 0)?$item->lifecycle->name:null, [
                    'class'=>'form-control', 'id'=>'lifecycle_id',
                    'disabled',
                ])
            }}
        </div>
    </div>

    @if(isset($item->lifecycle_id) && $item->lifecycle_id > 0 && $item->permission_to_sell == 'Y' && $item->lifecycle->name == 'Private Sale' && $item->private_sale_type == null)
    <div class="row" id="divItemLifecycle">
        <div class="form-group col-md-12">
            <button type="button" class="btn btn-md btn-primary" id="btnPrivateSale">{{ __('Private Sale') }}</button>
        </div>
    </div>
    @endif

    <div id="divPrivateSale" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
        @include('item::itemshow.private_sale_details')
    </div>

    @if($item->private_sale_type != null)
    <div style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
        @include('item::itemshow.show_private_sale_details')        
    </div>
    @endif

	<div id="itemlifecycle">
        @foreach($itemlifecycles as $itemlifecycle)
		    <div class="" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
                @if($itemlifecycle['type'] == 'auction')
                <div class="row">
                    <label class="form-control-label col-md-3">{{ __('Auction') }}</label>
                    <div class="form-group col-6 col-md-6 col-xl-6">
                        {{ Form::select('auction_id', $auctions, $itemlifecycle['reference_id'], [
                                'class'=>'form-control', 'id'=>'auction_id',
                                'disabled',
                            ])
                        }}
                    </div>
                </div>
                @endif

                @php
                    $periodlabel = 'Period';
                    $price_addon = 'SGD';

                    if($itemlifecycle['type'] == 'auction'){
                        $pricelabel = 'Opening Bid';
                    }

                    if($itemlifecycle['type'] == 'marketplace'){
                        $pricelabel = 'Marketplace Price';
                    }

                    if($itemlifecycle['type'] == 'clearance'){
                        $pricelabel = 'Clearance Price';
                    }

                    if($itemlifecycle['type'] == 'privatesale'){
                        $pricelabel = 'Private Sale Price';
                    }

                    if($itemlifecycle['type'] == 'storage'){
                        $pricelabel = 'Storage Price';
                        $price_addon = 'SGD/Day';
                        $periodlabel = 'Storage First Period';
                    }
                @endphp

                <div class="row">
                    <label class="form-control-label col-md-3">{{ $pricelabel }}</label>
                    <div class="form-group col-6 col-md-6 col-xl-6">
                        <div class="input-group">
                            {{ Form::text('price', $itemlifecycle['price'], [
                                    'class'=>'form-control', 'id'=>'price',
                                    'disabled',
                                ])
                            }}
                            <span class="input-group-addon">{{ $price_addon }}</span>
                        </div>
                    </div>
                </div>

                @if($itemlifecycle['type'] != 'auction')
                <div class="row">
                    <label class="form-control-label col-md-3">{{ $periodlabel }}</label>
                    <div class="form-group col-6 col-md-6 col-xl-6">
                        <div class="input-group">
                            {{ Form::text('period', $itemlifecycle['period'], [
                                    'class'=>'form-control', 'id'=>'period',
                                    'disabled',
                                ])
                            }}
                            <span class="input-group-addon">Days</span>
                        </div>
                    </div>
                </div>
                @endif

                @if($itemlifecycle['type'] == 'storage')
                <div class="row">
                    <label class="form-control-label col-md-3">{{ __('Storage Second Period') }}</label>
                    <div class="form-group col-6 col-md-6 col-xl-6">
                        <div class="input-group">
                            {{ Form::text('second_period', $itemlifecycle['second_period'], [
                                    'class'=>'form-control', 'id'=>'second_period',
                                    'disabled',
                                ])
                            }}
                            <span class="input-group-addon">Days</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="form-control-label col-md-3" for="is_indefinite_period">{{ __('Storage Period Indefinite') }}</label>
                    <div class="form-group col-md-1">
                        {{ Form::checkbox('is_indefinite_period','Y', ($itemlifecycle['is_indefinite_period'] == 'Y')?true:null, [
                                'class'=>'form-control', 'id'=>'is_indefinite_period',
                                'disabled',
                            ])
                        }}
                    </div>
                </div>
                @endif
            </div>
        @endforeach
	</div>
</div>