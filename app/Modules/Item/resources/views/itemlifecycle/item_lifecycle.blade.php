{!! Form::model($item, ['route'  => ['item.items.lifecycle_update', $item], 'method' => 'POST', 'id'=>'frmItemLifecycle', 'data-parsley-validate'=>'true', 'autocomplete' => 'off', "data-parsley-excluded"=>"input[type=hidden], [disabled], :hidden" ]) !!}

<div class="card-block">
    <input type="hidden" name="tab_name" value="item_lifecycle">
    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Valuer') }}</label>
            {{ Form::select('valuer_id', $valuers, old('valuer_id', isset($item->valuer_id)? (integer)$item->valuer_id:null), [
                    'class'=>'form-control',
                ])
            }}

            @if ($errors->has('valuer_id'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('valuer_id') }}</div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('GST Rate') }}</label>
            <input type="hidden" name="vat_tax_rate" value="{{ $gst_rate }}">
            {{ Form::text('gst_rate', $gst_rate, [
                    'class' => 'form-control' . ($errors->has('gst_rate') ? ' is-invalid' : ''),
                    'data-parsley-type'=>'number',
                    'placeholder' => __('GST Rate'),
                    'disabled'
                ])
            }}
        </div>

        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Low Estimate') }} <span style="color: red;">*</span></label>
            {{ Form::text('low_estimate', isset($item->low_estimate)?$item->low_estimate:null, [
                    'class' => 'form-control' . ($errors->has('low_estimate') ? ' is-invalid' : ''),
                    'data-parsley-type'=>'number',
                    'placeholder' => __('Low Estimate'),
                    'id' => 'low_estimate',
                    'required'
                ])
            }}

            @if ($errors->has('low_estimate'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('low_estimate') }}</div>
            @endif
        </div>

        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('High Estimate') }} <span style="color: red;">*</span></label>
            {{ Form::text('high_estimate', isset($item->low_estimate)?$item->high_estimate:null, [
                    'class' => 'form-control' . ($errors->has('high_estimate') ? ' is-invalid' : ''),
                    'data-parsley-type'=>'number',
                    'placeholder' => __('High Estimate'),
                    'id' => 'high_estimate',
                    'required'
                ])
            }}

            @if ($errors->has('high_estimate'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('high_estimate') }}</div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Reserve') }} <span class="reserve_span" style="color: red;">*</span></label>
            <div class="input-group" style="width: 100%">
                <span class="input-group-addon">
                    {{ Form::checkbox('is_reserve', 'Y', ($item->is_reserve == 'Y')?'checked':'', [
                            'id' => "is_reserve",
                            'data-type' => 'reserve',
                            'data-type_span' => 'reserve_span',
                        ])
                    }}
                </span>
                {{ Form::text('reserve', null, [
                        'class' => 'form-control' . ($errors->has('reserve') ? ' is-invalid' : ''),
                        'data-parsley-type'=>'number',
                        'placeholder' => __('Reserve'),
                        'id'=>'reserve',
                        "data-parsley-errors-container"=>"#error_reserve",
                    ])
                }}
            </div>
            <div id='error_reserve'></div>

            @if ($errors->has('reserve'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('reserve') }}</div>
            @endif
        </div>

        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Hotlotz Own Stock?') }}</label>
            <div class="input-group">
                <label class="checkbox-inline" for="is_hotlotz_own_stock">
                    {{ Form::checkbox('is_hotlotz_own_stock', 'Y', ($item->is_hotlotz_own_stock == 'Y')?'checked':'', [
                            'id' => "is_hotlotz_own_stock",
                        ])
                    }}
                </label>
            </div>

            @if ($errors->has('is_hotlotz_own_stock'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('is_hotlotz_own_stock') }}</div>
            @endif
        </div>
    </div>

    <div class="row divHotlotzOwnStock">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Supplier') }}</label>
            {{ Form::text('supplier', null, [
                    'class' => 'form-control' . ($errors->has('supplier') ? ' is-invalid' : ''),
                    'placeholder' => __('Supplier'),
                    'id'=>'supplier'
                ])
            }}

            @if ($errors->has('supplier'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('supplier') }}</div>
            @endif
        </div>
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Purchase Cost') }} <span style="color:red">*</span></label>
            {{ Form::text('purchase_cost', null, [
                    'class' => 'form-control' . ($errors->has('purchase_cost') ? ' is-invalid' : ''),
                    'data-parsley-type'=>'number',
                    'placeholder' => __('Purchase Cost'),
                    'id'=>'purchase_cost',
                    'required'
                ])
            }}

            @if ($errors->has('purchase_cost'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('purchase_cost') }}</div>
            @endif
        </div>
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('GST by Supplier') }} <span style="color:red">*</span></label>
            {{ Form::text('supplier_gst', null, [
                    'class' => 'form-control' . ($errors->has('supplier_gst') ? ' is-invalid' : ''),
                    'data-parsley-type'=>'number',
                    'id'=>'supplier_gst',
                    'required'
                ])
            }}

            @if ($errors->has('supplier_gst'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('supplier_gst') }}</div>
            @endif
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12 col-md-12 col-xl-12">
            <label class="form-control-label">{{ __('Lifecycle') }} <span style="color: red;">*</span></label>
            {{ Form::select('lifecycle_id', $lifecycles, $item->lifecycle_id, [
                'class'=>'form-control', 'id'=>'lifecycle_id',
                ($item->lifecycle_id != 13)?'required':null,
                ($item->status != 'Pending')?'disabled':null
                ], [13 => ['disabled'=>true]])
            }}
            <input type="hidden" name="hidden_lifecycle_id" value="{{ $item->lifecycle_id }}" id="hidden_lifecycle_id">

            @if ($errors->has('lifecycle_id'))
                <div class="invalid-feedback">{{ $errors->first('lifecycle_id') }}</div>
            @endif
        </div>
    </div>

    <!-- <div class="form-row" id="divAddNewStage">
    	<div class="form-group col-md-8">
		   	<button type="button" class="btn btn-success" id='addButton'><i class="zmdi zmdi-plus"></i>Add new Stage</button>
		</div>
	</div> -->

	<div id="itemlifecycle">

	</div>
</div>

<div class="card-footer">
    @if( $item->permission_to_sell != 'Y' )
        <button class="btn btn-outline-success" id="btnLifecycleSave">{{ __('Save') }}</button>
    @endif
    <a href="{{ route('item.items.show',['item'=>$item])}}" class="btn btn-outline-danger">{{ __('Cancel') }}</a>
</div>

{!! Form::close() !!}

@section('scripts')
@parent    

@include('item::itemlifecycle.custom_lifecycle_template')
@include('item::itemlifecycle.lifecycle_templates')
@include('item::itemlifecycle.itemlifecyclejs')

@stop