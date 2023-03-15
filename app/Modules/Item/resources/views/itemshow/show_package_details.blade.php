@if($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee')
<div style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
	<div class="form-row">
        @if($item->fee_type == 'sales_commission')
		<div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Seller’s Commission') }} <span style="color:red">*</span></label>
	        <div class="input-group">
	        	<input type="text" name="sales_commission" value="{{ ($item_fee && $item->fee_type == 'sales_commission' && $item_fee->sales_commission != null)?$item_fee->sales_commission:'20' }}" class="form-control" disabled >
	        	<span class="input-group-addon">%</span>
	    	</div>
	    </div>
        @endif

        @if($item->fee_type == 'fixed_cost_sales_fee')
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Fixed Cost Sales Fee') }} <span style="color:red">*</span></label>
	        <div class="input-group">
	        	<span class="input-group-addon">$</span>
		        <input type="text" name="fixed_cost_sales_fee" value="{{ ($item_fee && $item->fee_type == 'fixed_cost_sales_fee' && $item_fee->fixed_cost_sales_fee != null)?$item_fee->fixed_cost_sales_fee:'40' }}" class="form-control" disabled >
		    </div>
	    </div>
        @endif

        <!-- @if($item->fee_type == 'hotlotz_owned_stock')
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Hotlotz Owned Stock') }} <span style="color:red">*</span></label>
	        <input type="text" name="hotlotz_owned_stock" value="{{ ($item_fee && $item->fee_type == 'hotlotz_owned_stock')?$item_fee->hotlotz_owned_stock:null }}" class="form-control" disabled >
	    </div>
        @endif -->
	</div>

    @if($item->fee_type == 'sales_commission')
	<div class="form-row">
        <div class="col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Performance Commission Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('performance_commission_setting', 1, $fee_settings['performance_commission_setting'], ['class' => 'switch-input', 'id' => 'performance_commission', 'disabled']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>
    	    </div>
        </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Performance Commission') }} <span style="color:red">*</span></label>
	        <div class="input-group">
	        	<span class="input-group-addon">+</span>
	        	<input type="text" id="txt_performance_commission" name="performance_commission" value="{{ ($item_fee && $item->fee_type == 'sales_commission' && $item_fee->performance_commission != null)?$item_fee->performance_commission:'2' }}" class="form-control" disabled >
	        	<span class="input-group-addon">%</span>
	    	</div>
	    </div>

		<div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Minimum Commission Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                {{ Form::hidden('minimum_commission_setting', 0) }}
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('minimum_commission_setting', 1, $fee_settings['minimum_commission_setting'], ['class' => 'switch-input', 'id' => 'minimum_commission', 'disabled']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>
            </div>
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Minimum Commission') }} <span style="color:red">*</span></label>
	        <div class="input-group">
	        	<span class="input-group-addon">$</span>
	        	<input type="text" id="txt_minimum_commission" name="minimum_commission" value="{{ ($item_fee && $item->fee_type == 'sales_commission' && $item_fee->minimum_commission != null)?$item_fee->minimum_commission:'40' }}" class="form-control" disabled >
	        </div>
	    </div>
	</div>
    @endif

    @if($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee')
	<div class="form-row">
		<div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Insurance Fee Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('insurance_fee_setting', 1, $fee_settings['insurance_fee_setting'], ['class' => 'switch-input', 'id' => 'insurance_fee', 'disabled']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>
            </div>
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Insurance Fee') }} <span style="color:red">*</span></label>
	        <div class="input-group">
	        	<input type="text" id="txt_insurance_fee" name="insurance_fee" value="{{ ($item_fee && ($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee') && $item_fee->insurance_fee != null)?$item_fee->insurance_fee:'1.5' }}" class="form-control" disabled >
	        	<span class="input-group-addon">%</span>
	        </div>
	    </div>

		<div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Listing Fee Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('listing_fee_setting', 1, $fee_settings['listing_fee_setting'], ['class' => 'switch-input', 'id' => 'listing_fee', 'disabled']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>
            </div>
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Listing Fee') }} <span style="color:red">*</span></label>
	        <div class="input-group">
	        	<span class="input-group-addon">$</span>
		        <input type="text" id="txt_listing_fee" name="listing_fee" value="{{ ($item_fee && ($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee') && $item_fee->listing_fee != null)?$item_fee->listing_fee:'40' }}" class="form-control" disabled >
		    </div>
	    </div>

		<div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Unsold Fee Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('unsold_fee_setting', 1, $fee_settings['unsold_fee_setting'], ['class' => 'switch-input', 'id' => 'unsold_fee', 'disabled']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>
            </div>
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Unsold Fee') }} <span style="color:red">*</span></label>
	        <div class="input-group">
	        	<span class="input-group-addon">$</span>
		        <input type="text" id="txt_unsold_fee" name="unsold_fee" value="{{ ($item_fee && ($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee') && $item_fee->unsold_fee != null)?$item_fee->unsold_fee:'40' }}" class="form-control" disabled >
		    </div>
	    </div>

		<div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Withdrawal Fee Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('withdrawal_fee_setting', 1, $fee_settings['withdrawal_fee_setting'], ['class' => 'switch-input', 'id' => 'withdrawal_fee', 'disabled']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>
            </div>
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Withdrawal Fee') }} <span style="color:red">*</span></label>
	        <div class="input-group">
	        	<span class="input-group-addon">$</span>
		        <input type="text" id="txt_withdrawal_fee" name="withdrawal_fee" value="{{ ($item_fee && ($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee') && $item_fee->withdrawal_fee != null)?$item_fee->withdrawal_fee:'60' }}" class="form-control" disabled >
		    </div>
	    </div>

		<div class="form-group col-12 col-md-6 col-xl-6">
            <label class="form-control-label">{{ __('I/C Details') }} <span style="color:red">*</span></label>
            <div class="form-group">
                {{ Form::hidden('ic_details', 0) }}
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('ic_details', 1, old('ic_details', $fee_settings['ic_details']), ['class' => 'switch-input ic_details', 'id' => 'ic_details', 'disabled']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>
            </div>
        </div>
        <div class="form-group col-12 col-md-3 col-xl-3">
            <label class="form-control-label">{{ __('I/C Amount') }} <span style="color:red">*</span></label>
            <div class="input-group">
	            <input type="text" id="txt_ic_amount" name="ic_amount" value="{{ ($item_fee && ($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee') && $item_fee->ic_amount != null)?$item_fee->ic_amount:null }}" class="form-control" disabled  >
				<span class="input-group-addon">%</span>
	        </div>
        </div>
        <div class="form-group col-12 col-md-3 col-xl-3">
            <label class="form-control-label">{{ __('I/C Commissioner') }} <span style="color:red">*</span></label>
            {{ Form::text('ic_commissioner', ($ic_commissioner != null)?($ic_commissioner->ref_no.'_'.$ic_commissioner->firstname.' '.$ic_commissioner->lastname):null, [
                    'class'=>'form-control',
                    'disabled'
                ])
            }}
        </div>
	</div>
    @endif
</div>
@endif