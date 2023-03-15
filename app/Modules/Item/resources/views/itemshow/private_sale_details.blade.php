{!! Form::model($item, ['route'  => ['item.items.private_sale', $item->id], 'method' => 'POST', 'id'=>'frmPrivateSaleItem', 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}
    <div class="row">
        <div class="form-group col-md-4">
            <label class="col-form-label">Type <span style="color:red">*</span></label>
            <div class="input-group">
                <label class="radio-inline" for="auction_type">
                    {{ Form::radio('private_sale_type', 'auction', ($item->private_sale_type == 'auction')?true:null, [
                            'class' => 'private_sale_type',
                            'id' => "auction_type",
                            'required',
                            'data-parsley-errors-container'=>"#error_private_sale_type",
                            'v-model' => 'psType'
                        ])
                    }}
                    Auction
                    &nbsp;&nbsp;
                </label>
                <label class="radio-inline" for="privatesale_type">
                    {{ Form::radio('private_sale_type', 'privatesale', ($item->private_sale_type == 'privatesale')?true:null, [
                            'class' => 'private_sale_type',
                            'id' => "privatesale_type",
                            'required',
                            'data-parsley-errors-container'=>"#error_private_sale_type",
                            'v-model' => 'psType'
                        ])
                    }}
                    Private Sale
                    &nbsp;&nbsp;
                </label>
            </div>
            <div id="error_private_sale_type"></div>
        </div>
        <div class="form-group col-md-4" v-show="psType == 'auction'" >
            <label for="auction_id" class="col-form-label">Auction <span style="color:red">*</span></label>
            {{ Form::select('auction_id', $auctions, null, [
                    'class' => 'form-control form-control-md', 'id'=>'auction_id',
                    'required',
                ])
            }}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label class="col-form-label">Price <span style="color:red">*</span></label>
            {{ Form::text('price', null, [
                    'class' => 'form-control form-control-md',
                    'id'=>'price',
                    'data-parsley-type'=>'number',
                    'required',
                ])
            }}
        </div>
        <div class="form-group col-md-4">
            <label class="col-form-label">Buyer Premium <span style="color:red">*</span></label>
            <div class="input-group">
                {{ Form::text('buyer_premium', null, [
                        'class' => 'form-control form-control-md',
                        'data-parsley-type'=>'number',
                        'required',
                        'data-parsley-errors-container'=>'#error_buyer_premium',
                    ])
                }}
                <span class="input-group-addon">%</span>
            </div>
            <div id="error_buyer_premium"></div>
        </div>
        <div class="form-group col-md-4">
            <label class="col-form-label">Buyer <span style="color:red">*</span></label>
            {{ Form::select('buyer_id', [], old('buyer_id', isset($item->buyer_id)?'selected':null), [
                    'class'=>'select2 form-control',
                    'id'=>'buyer_id',
                    'required',
                    'data-parsley-errors-container' => "#error_buyer_id",
                ])
            }}
            <div id="error_buyer_id"></div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <button class="btn btn-success">{{ __('Sold') }}</button>
        </div>
    </div>
{!! Form::close() !!}

@section('scripts')
@parent
<script type="text/javascript">

    $('#divPrivateSale').hide();
    $('#btnPrivateSale').click(function(){
        $('#divPrivateSale').toggle();
    });
</script>
@stop