{!! Form::model($items, ['id'=>"itemFilterForm", 'route'  => ['marketplace.marketplaces.sold_item_filter'], 'method' => 'POST', 'data-parsley-validate'=>"true"]) !!}

<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-3">
        <label class="form-control-label">{{ __('Filter by Seller') }}</label>
        {{ Form::select('seller', [], null, [
                'class'=>'select2 form-control',
                'id'=>'seller',
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-4 col-xl-3">
        <label class="form-control-label">{{ __('Filter by Buyer') }}</label>
        {{ Form::select('buyer', [], null, [
                'class'=>'select2 form-control',
                'id'=>'buyer',
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-3 col-xl-3">
        <label class="form-control-label">{{ __('Start Date') }}</label>
        <input type="datetime-local" id="start_date" name="start_date">
    </div>
    <div class="form-group col-12 col-md-3 col-xl-3">
        <label class="form-control-label">{{ __('End Date') }}</label>
        <input type="datetime-local" id="end_date" name="end_date">
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-3 col-xl-3">
        <label class="form-control-label">{{ __('Filter by Tag') }}</label>
        <br>
        {{ Form::select('tag', $tags, null, [
                'class' => 'form-control',
                'id' => 'tag'
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
     <div class="col-12 col-md-10 col-xl-10 mt-2">
        <div class="form-group">
            <form method="post" target="_blank" id="generateDispatchForm">
                @csrf
                <input type="hidden" name="items" id="genreateDispatchItems">
                <input type="hidden" name="additional_note" id="genreateDispatchNotes">
            </form>
            <button type="button" class="btn btn-outline-warning mt-3 float-right" onclick="generateDispatch('buyer')">{{ __('Generate Dispatch (Buyer)') }}</button>
            <button type="button" class="btn btn-outline-danger mt-3 float-right" onclick="generateDispatch('seller')">{{ __('Generate Dispatch (Seller)') }}</button>
            <form action="{{ route('marketplace.marketplaces.generateBuyerLabel') }}" method="post" target="_blank">
                @csrf
                <input type="hidden" name="items" id="generateBuyerPdfBaseOnItem">
                <button type="submit" class="btn btn-outline-info mt-3 float-right">{{ __('Generate Buyer Label') }}</a>
            </form>
        </div>
    </div>
</div>
