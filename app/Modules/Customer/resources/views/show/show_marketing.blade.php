<div class="form-row">
    <div class="form-group col-md-12">
        <strong class="form-control-label" style="font-size:large;">{{ __('Marketing Preference') }}
            &nbsp;&nbsp;</strong>
        <label>
            {{ Form::radio('exclude_marketing_material', 1, ($customer->exclude_marketing_material == 1)? 'checked':'', ['disabled']) }}
            {{ __('Exclude from marketing material') }}
        </label>
        <label>
            {{ Form::radio('exclude_marketing_material', 0, ($customer->exclude_marketing_material == 0)?'checked':'', ['disabled']) }}
            {{ __('Include from marketing material') }}
        </label>
    </div>
</div>
<!--
<div class="form-row" style="margin-left:10px;">
    <div class="form-group col-md-4">
        <div class="form-check form-check-inline">
            {{ Form::checkbox('marketing_auction', 1, ($customer->marketing_auction == 1)?true:false, ['disabled'] ) }}
            {{ __('1. Auction Updates') }}
        </div>
    </div>

    <div class="form-group col-md-4">
        <div class="form-check form-check-inline">
            {{ Form::checkbox('marketing_marketplace', 1, ($customer->marketing_marketplace == 1)?true:false, ['disabled'] ) }}
            {{ __('2. Marketplace Updates') }}
        </div>
    </div>

    <div class="form-group col-md-4">
        <div class="form-check form-check-inline">
            {{ Form::checkbox('marketing_chk_events', 1, ($customer->marketing_chk_events == 1)?true:false, ['disabled'] ) }}
            {{ __('3. Events') }}
        </div>
    </div>
</div>

<div class="form-row" style="margin-left:10px;">
    <div class="form-group col-md-4">
        <div class="form-check form-check-inline">
            {{ Form::checkbox('marketing_chk_congsignment_valuation', 1, ($customer->marketing_chk_congsignment_valuation == 1)?true:false, ['disabled'] ) }}
            {{ __('4. Consignment & Valuation') }}
        </div>
    </div>

    <div class="form-group col-md-4">
        <div class="form-check form-check-inline">
            {{ Form::checkbox('marketing_hotlotz_quarterly', 1, ($customer->marketing_hotlotz_quarterly == 1)?true:false, ['disabled'] ) }}
            {{ __('5. Hotlotz Quarterly Newsletter') }}
        </div>
    </div>
</div> -->

<div class="form-row">
    <div class="col-md-12">
        <strong class="form-control-label" style="font-size:large;">{{ __('Category Interests') }}</strong>
        <div class="form-group col-md-12">
            <ul>
                @foreach($categories as $id => $value)
                @if(in_array($id,$category_interests))
                <li>{{$value}}</li>
                @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>