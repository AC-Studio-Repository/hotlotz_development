<div class="form-row">
    <div class="form-group col-md-12">
        <strong class="form-control-label" style="font-size:large;">{{ __('Marketing Preference') }}
            &nbsp;&nbsp;</strong>
        <label>
            <input type="radio" value="1" id="exampleRadios1" name="exclude_marketing_material" {{ $customer->exclude_marketing_material == 1 ? 'checked' : '' }}>
            {{ __('Exclude from marketing material')  }}
        </label>
         <label>
            <input type="radio" value="0" id="exampleRadios2" name="exclude_marketing_material" {{ $customer->exclude_marketing_material == 0 ? 'checked' : '' }}>
            {{ __('Include from marketing material') }}
        </label>
    </div>
</div>

<!-- <div class="form-row" style="margin-left:10px;">
    <div class="form-group col-md-4">
        <div class="form-check form-check-inline">
            {{ Form::checkbox('marketing_auction', 1, old('marketing_auction', ($customer->marketing_auction == 1)?true:false), ['id' => 'marketing_auction']) }}
            {{ __('1. Auction Updates') }}
        </div>
    </div>

    <div class="form-group col-md-4">
        <div class="form-check form-check-inline">
            {{ Form::checkbox('marketing_marketplace', 1, old('marketing_marketplace', ($customer->marketing_marketplace == 1)?true:false), ['id' => 'marketing_marketplace']) }}
            {{ __('2. Marketplace Updates') }}
        </div>
    </div>

    <div class="form-group col-md-4">
        <div class="form-check form-check-inline">
            {{ Form::checkbox('marketing_chk_events', 1, old('marketing_chk_events', ($customer->marketing_chk_events == 1)?true:false),['id' => 'marketing_chk_events']) }}
            {{ __('3. Events') }}
        </div>
    </div>
</div>

<div class="form-row" style="margin-left:10px;">
    <div class="form-group col-md-4">
        <div class="form-check form-check-inline">
            {{ Form::checkbox('marketing_chk_congsignment_valuation', 1, old('marketing_chk_congsignment_valuation', ($customer->marketing_chk_congsignment_valuation == 1)?true:false), ['id' => 'marketing_chk_congsignment_valuation']) }}
            {{ __('4. Consignment & Valuation') }}
        </div>
    </div>

    <div class="form-group col-md-4">
        <div class="form-check form-check-inline">
            {{ Form::checkbox('marketing_hotlotz_quarterly', 1, old('marketing_hotlotz_quarterly', ($customer->marketing_hotlotz_quarterly == 1)?true:false), ['id' => 'marketing_hotlotz_quarterly']) }}
            {{ __('5. Hotlotz Quarterly Newsletter') }}
        </div>
    </div>
</div> -->

<div class="form-row">
    <h5 class="form-control-label col-md-2"><strong>{{ __('Category Interests') }}</strong></h5>
    <div class="col-md-10">
        <div class="form-group">
            <select name="category_interests[]" title="category_interests[]" class="form-control category_interests"
                size="10" multiple="multiple">
                @foreach($categories as $id => $value)
                <?php $selected = (in_array($id, $category_interests))?"selected":null; ?>
                <option value="{{$id}}" {{$selected}}>{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script type="text/javascript">
    $('#exclude_marketing_material').change(function() {
        if(this.checked) {
            $('#marketing_auction').prop("checked", false);
            $('#marketing_marketplace').prop("checked", false);
            $('#marketing_chk_events').prop("checked", false);
            $('#marketing_chk_congsignment_valuation').prop("checked", false);
            $('#marketing_hotlotz_quarterly').prop("checked", false);
        }
    });

    $('#marketing_auction').change(function() {
        if(this.checked) {
            $('#exclude_marketing_material').prop("checked", false);
        }
    });

    $('#marketing_marketplace').change(function() {
        if(this.checked) {
            $('#exclude_marketing_material').prop("checked", false);
        }
    });

    $('#marketing_chk_events').change(function() {
        if(this.checked) {
            $('#exclude_marketing_material').prop("checked", false);
        }
    });

    $('#marketing_chk_congsignment_valuation').change(function() {
        if(this.checked) {
            $('#exclude_marketing_material').prop("checked", false);
        }
    });

    $('#marketing_hotlotz_quarterly').change(function() {
        if(this.checked) {
            $('#exclude_marketing_material').prop("checked", false);
        }
    });
</script>
@stop
