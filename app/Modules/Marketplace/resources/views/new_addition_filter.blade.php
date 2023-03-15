{!! Form::model($items, ['id'=>"itemFilterForm", 'route'  => ['marketplace.marketplaces.new_addition_filter'], 'method' => 'POST', 'data-parsley-validate'=>"true"]) !!}

<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Filter by Seller') }}</label>
        {{ Form::select('seller', [], null, [
                'class'=>'select2 form-control',
                'id'=>'seller',
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Filter by Marketplace or Clearance') }}</label>
        {{ Form::select('status', $statuses, null, [
                'class'=>'form-control',
                'id'=>'new_addition_status',
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12 text-right">
        <button type="button" class="btn btn-md btn-outline-primary" id="btnSearch">{{ __('Search') }}</button>
        <button type="button" class="btn btn-md btn-outline-success float-right" id="btnResetAll">Reset All</button>
    </div>
</div>

<hr>
<div class="row">
    <div class="form-group col-12 col-md-2 col-xl-2">
        <label class="form-control-label">{{ __('Per page') }}</label>
        {{ Form::select('per_page', ['10'=>'10', '50'=>'50', '100'=>'100'], null, [
                'class'=>'form-control', 'id'=>'per_page'
            ])
        }}
    </div>

{!! Form::close() !!}

    <div class="form-group col-12 col-md-10 col-xl-10 text-right">
        <form action="{{ route('marketplace.marketplaces.generateLabel') }}" method="post" target="_blank">
            @csrf
            <input type="hidden" name="items" id="generatePdfBaseOnItem">
            <button type="submit" class="btn btn-outline-warning mt-3">{{ __('Generate Label') }}</button>
        </form>
    </div>

</div>
