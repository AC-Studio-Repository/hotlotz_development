{!! Form::model($items, ['id'=>"itemFilterForm", 'route'  => ['item.items.filter'], 'method' => 'POST', 'data-parsley-validate'=>"true"]) !!}

<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Search Item') }}</label>
        {{ Form::text('search_text', null, [
                'class'=>'form-control input-search', 'id'=>'search_text'
            ])
        }}
    </div>

    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Filter by Permission to Sell') }}</label>
        <select name="permission_to_sell" id="permission_to_sell" class="form-control">
            <option value="">{{ __("--Select Permission--") }}</option>
            <option value="Y">{{ __("Yes") }}</option>
            <option value="N">{{ __("No") }}</option>
        </select>
    </div>

    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Filter by Lifecycle Type') }}</label>
        {{ Form::select('lifecycle', [''=>'All'] + $lifecycles, null, [
                'class'=>'form-control', 'id'=>'lifecycle'
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Filter by Auction') }}</label>
        {{ Form::select('auction', [''=>'All'] + $auctions, null, [
                'class'=>'form-control', 'id'=>'auction'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Filter by Seller') }}</label>
        {{ Form::select('seller', [], null, [
                'class'=>'select2 form-control',
                'id'=>'seller',
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">Filter by Marketplace</label>
        <div class="input-group">
            <label class="radio-inline" for="bulk_true">
                {{ Form::radio('marketplace', 'marketplace_and_clearance', null, ['id' => "marketplace_only", 'class'=>'marketplace']) }}
                All
                &nbsp;&nbsp;
            </label>
            <label class="radio-inline" for="bulk_true">
                {{ Form::radio('marketplace', 'marketplace_only', null, ['id' => "marketplace_only", 'class'=>'marketplace']) }}
                Marketplace Only
                &nbsp;&nbsp;
            </label>
            <label class="radio-inline" for="bulk_false">
                {{ Form::radio('marketplace', 'clearance_only', null, ['id' => "clearance_only", 'class'=>'marketplace']) }}
                Clearance Only
            </label>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Filter by Category') }}</label>
        <br>
        {{ Form::select('category[]', $categories, null, [
                'class' => 'form-control multiselect',
                'id'=>'category',
                'multiple'=>'multiple'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Filter by Action Required') }}</label>
        <br>
        {{ Form::select('action_required[]', $actions, null, [
                'class' => 'form-control multiselect',
                'id'=>'action_required',
                'multiple'=>'multiple'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Filter by Status') }}</label>
        <br>
        {{ Form::select('item_status[]', $statuses, null, [
                'class' => 'form-control multiselect',
                'id'=>'item_status',
                'multiple'=>'multiple'
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
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
</div>

{!! Form::close() !!}
