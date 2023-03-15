{!! Form::model($auctions, ['id'=>"auctionFilterForm", 'route'  => ['auction.auctions.filter'], 'method' => 'POST', 'data-parsley-validate'=>"true"]) !!}

    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Search Auction') }}</label>
            {{ Form::text('search_text', null, [
                    'class'=>'form-control', 'id'=>'search_text'
                ])
            }}
        </div>
        <div class="col-12 col-md-4 col-xl-4">
        	<label class="form-control-label">&nbsp;</label>
        	<div class="form-group">
	            <button type="button" class="btn btn-outline-primary" id="btnSearch">{{ __('Search') }}</button>
	        </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-12 col-md-2 col-xl-2">
            <label class="form-control-label">{{ __('Per page') }}</label>
            {{ Form::select('per_page', ['10'=>'10', '50'=>'50', '100'=>'100'], null, [
                    'class'=>'form-control', 'id'=>'per_page'
                ])
            }}
        </div>
    </div>

    <input type="hidden" name="closed" value="{{ app('request')->input('closed') }}">
{!! Form::close() !!}