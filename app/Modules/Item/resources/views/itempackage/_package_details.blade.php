<div v-show="feeType == 'sales_commission' || feeType == 'fixed_cost_sales_fee'" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;" id="divSalesAndFixed">
	<input type="hidden" name="item_fee_structure_id" id="item_fee_structure_id" value="{{ ($item_fee)?$item_fee->id:null }}">

	<div class="form-row">
		<div class="form-group col-12 col-md-6 col-xl-6" v-show="feeType == 'sales_commission'">
	        <label class="form-control-label">{{ __('Seller’s Commission') }} <span style="color:red">*</span></label>
            <input type="hidden" name="sales_commission_currency" value="%">
            <div class="input-group">
    	        <input type="text" name="sales_commission" value="{{ ($item_fee && $item->fee_type == 'sales_commission' && $item_fee->sales_commission != null)?$item_fee->sales_commission:'20' }}" class="form-control" required  >
                <span class="input-group-addon">%</span>
    	    </div>
        </div>

	    <div class="form-group col-12 col-md-6 col-xl-6" v-show="feeType == 'fixed_cost_sales_fee'">
    	    <label class="form-control-label">{{ __('Fixed Cost Sales Fee') }} <span style="color:red">*</span></label>
            <input type="hidden" name="fixed_cost_sales_fee_currency" value="$">
            <div class="input-group">
                <span class="input-group-addon">$</span>
    	        <input type="text" name="fixed_cost_sales_fee" value="{{ ($item_fee && $item->fee_type == 'fixed_cost_sales_fee' && $item_fee->fixed_cost_sales_fee != null)?$item_fee->fixed_cost_sales_fee:'40' }}" class="form-control" required  >
    	    </div>
        </div>

	    <!-- <div class="form-group col-12 col-md-6 col-xl-6" v-show="feeType == 'hotlotz_owned_stock'">
	        <label class="form-control-label">{{ __('Hotlotz Owned Stock') }} <span style="color:red">*</span></label>
	        <input type="text" name="hotlotz_owned_stock" value="{{ ($item_fee && $item->fee_type == 'hotlotz_owned_stock')?$item_fee->hotlotz_owned_stock:null }}" class="form-control" required  >
	    </div> -->
	</div>

	<div class="form-row" v-show="feeType == 'sales_commission'">
        <div class="col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Performance Commission Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                {{ Form::hidden('performance_commission_setting', 0) }}
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('performance_commission_setting', 1, old('performance_commission_setting', $fee_settings['performance_commission_setting']), ['class' => 'switch-input fee_setting', 'id' => 'performance_commission']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>

                @if ($errors->has('performance_commission_setting'))
                    <input type="text" hidden class="form-control is-invalid">
                    <div class="invalid-feedback">{{ $errors->first('performance_commission_setting') }}</div>
                @endif
    	    </div>
        </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Performance Commission') }} <span style="color:red">*</span></label>
            <input type="hidden" name="performance_commission_currency" value="%">
	        <div class="input-group">
                <span class="input-group-addon">+</span>
                <input type="text" id="txt_performance_commission" name="performance_commission" value="{{ ($item_fee && $item->fee_type == 'sales_commission' && $item_fee->performance_commission != null)?$item_fee->performance_commission:'2' }}" class="form-control" required  >
                <span class="input-group-addon">%</span>
    	    </div>
        </div>

		<div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Minimum Commission Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                {{ Form::hidden('minimum_commission_setting', 0) }}
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('minimum_commission_setting', 1, old('minimum_commission_setting', $fee_settings['minimum_commission_setting']), ['class' => 'switch-input fee_setting', 'id' => 'minimum_commission']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>

                @if ($errors->has('minimum_commission_setting'))
                    <input type="text" hidden class="form-control is-invalid">
                    <div class="invalid-feedback">{{ $errors->first('minimum_commission_setting') }}</div>
                @endif
            </div>
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Minimum Commission') }} <span style="color:red">*</span></label>
            <input type="hidden" name="minimum_commission_currency" value="$">
            <div class="input-group">
                <span class="input-group-addon">$</span>
    	        <input type="text" id="txt_minimum_commission" name="minimum_commission" value="{{ ($item_fee && $item->fee_type == 'sales_commission' && $item_fee->minimum_commission != null)?$item_fee->minimum_commission:'40' }}" class="form-control" required  >
    	    </div>
        </div>
	</div>

	<div class="form-row" v-show="feeType == 'sales_commission' || feeType == 'fixed_cost_sales_fee'">
		<div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Insurance Fee Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                {{ Form::hidden('insurance_fee_setting', 0) }}
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('insurance_fee_setting', 1, old('insurance_fee_setting', $fee_settings['insurance_fee_setting']), ['class' => 'switch-input fee_setting', 'id' => 'insurance_fee']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>

                @if ($errors->has('insurance_fee_setting'))
                    <input type="text" hidden class="form-control is-invalid">
                    <div class="invalid-feedback">{{ $errors->first('insurance_fee_setting') }}</div>
                @endif
            </div>
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Insurance Fee') }} <span style="color:red">*</span></label>
            <input type="hidden" name="insurance_fee_currency" value="%">
            <div class="input-group">
    	        <input type="text" id="txt_insurance_fee" name="insurance_fee" value="{{ ($item_fee && ($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee') && $item_fee->insurance_fee != null)?$item_fee->insurance_fee:'1.5' }}" class="form-control" required  >
                <span class="input-group-addon">%</span>
            </div>
	    </div>

		<div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Listing Fee Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                {{ Form::hidden('listing_fee_setting', 0) }}
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('listing_fee_setting', 1, old('listing_fee_setting', $fee_settings['listing_fee_setting']), ['class' => 'switch-input fee_setting', 'id' => 'listing_fee']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>

                @if ($errors->has('listing_fee_setting'))
                    <input type="text" hidden class="form-control is-invalid">
                    <div class="invalid-feedback">{{ $errors->first('listing_fee_setting') }}</div>
                @endif
            </div>
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Listing Fee') }} <span style="color:red">*</span></label>
            <input type="hidden" name="listing_fee_currency" value="$">
            <div class="input-group">
                <span class="input-group-addon">$</span>
    	        <input type="text" id="txt_listing_fee" name="listing_fee" value="{{ ($item_fee && ($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee') && $item_fee->listing_fee != null)?$item_fee->listing_fee:'40' }}" class="form-control" required  >
    	    </div>
        </div>

		<div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Unsold Fee Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                {{ Form::hidden('unsold_fee_setting', 0) }}
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('unsold_fee_setting', 1, old('unsold_fee_setting', $fee_settings['unsold_fee_setting']), ['class' => 'switch-input fee_setting', 'id' => 'unsold_fee']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>

                @if ($errors->has('unsold_fee_setting'))
                    <input type="text" hidden class="form-control is-invalid">
                    <div class="invalid-feedback">{{ $errors->first('unsold_fee_setting') }}</div>
                @endif
            </div>
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Unsold Fee') }} <span style="color:red">*</span></label>
            <input type="hidden" name="unsold_fee_currency" value="$">
            <div class="input-group">
                <span class="input-group-addon">$</span>
    	        <input type="text" id="txt_unsold_fee" name="unsold_fee" value="{{ ($item_fee && ($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee') && $item_fee->unsold_fee != null)?$item_fee->unsold_fee:'40' }}" class="form-control" required  >
    	    </div>
        </div>

		<div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Withdrawal Fee Setting') }} <span style="color:red">*</span></label>
            <div class="form-group">
                {{ Form::hidden('withdrawal_fee_setting', 0) }}
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('withdrawal_fee_setting', 1, old('withdrawal_fee_setting', $fee_settings['withdrawal_fee_setting']), ['class' => 'switch-input fee_setting', 'id' => 'withdrawal_fee']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>

                @if ($errors->has('withdrawal_fee_setting'))
                    <input type="text" hidden class="form-control is-invalid">
                    <div class="invalid-feedback">{{ $errors->first('withdrawal_fee_setting') }}</div>
                @endif
            </div>
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Withdrawal Fee') }} <span style="color:red">*</span></label>
            <input type="hidden" name="withdrawal_fee_currency" value="$">
            <div class="input-group">
                <span class="input-group-addon">$</span>
    	        <input type="text" id="txt_withdrawal_fee" name="withdrawal_fee" value="{{ ($item_fee && ($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee') && $item_fee->withdrawal_fee != null)?$item_fee->withdrawal_fee:'60' }}" class="form-control" required  >
    	    </div>
	    </div>

        <div class="form-group col-12 col-md-6 col-xl-6">
            <label class="form-control-label">{{ __('I/C Details') }} <span style="color:red">*</span></label>
            <div class="form-group">
                {{ Form::hidden('ic_details', 0) }}
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('ic_details', 1, old('ic_details', $fee_settings['ic_details']), ['class' => 'switch-input ic_details', 'id' => 'ic_details']) }}
                    <span class="switch-label" data-on="On" data-off="Off"></span>
                    <span class="switch-handle"></span>
                </label>

                @if ($errors->has('ic_details'))
                    <input type="text" hidden class="form-control is-invalid">
                    <div class="invalid-feedback">{{ $errors->first('ic_details') }}</div>
                @endif
            </div>
        </div>
        <div class="form-group col-12 col-md-3 col-xl-3">
            <label class="form-control-label">{{ __('I/C Amount') }} <span style="color:red">*</span></label>
            <input type="hidden" name="ic_amount_currency" value="%">
            <div class="input-group">
                <input type="text" id="txt_ic_amount" name="ic_amount" value="{{ ($item_fee && ($item->fee_type == 'sales_commission' || $item->fee_type == 'fixed_cost_sales_fee') && $item_fee->ic_amount != null)?$item_fee->ic_amount:null }}" class="form-control txt_ic_details" required data-parsley-errors-container="#error_ic_amount"  >
                <span class="input-group-addon">%</span>
            </div>
            <div id="error_ic_amount"></div>
        </div>
        <div class="form-group col-12 col-md-3 col-xl-3">
            <label class="form-control-label">{{ __('I/C Commissioner') }} <span style="color:red">*</span></label>
            {{ Form::select('ic_commissioner', [], old('ic_commissioner', ($item_fee && isset($item_fee->ic_commissioner))?'selected':null), [
                    'class'=>'select2 form-control txt_ic_details' . ($errors->has('ic_commissioner') ? ' is-invalid' : ''),
                    'id'=>'ic_commissioner',
                    'required',
                    'data-parsley-errors-container' => "#error_ic_commissioner",
                ])
            }}
            <div id="error_ic_commissioner"></div>
            

            @if ($errors->has('ic_commissioner'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('ic_commissioner') }}</div>
            @endif
        </div>
	</div>
</div>

@section('scripts')
@parent
<script type="text/javascript">
    var ic_commissioner = {!! json_encode($item_fee->ic_commissioner ?? 0) !!};
    console.log('ic_commissioner : ',ic_commissioner);

    $(function() {

        $(".fee_setting").each(function(){
            checkedDisabledFields($(this));
        });

        $(".fee_setting").click(function() {
            checkedDisabledFields($(this));
        });

        checkedIcDetails($("#ic_details"));
        $("#ic_details").click(function() {
            checkedIcDetails($(this));
        });

        $('.btnCreateCustomer').click(function(){
            if($('#frmCustomer').parsley().validate() == true){
                $.ajax({
                    url: "/manage/customers/ajaxcreate",
                    type: 'post',
                    data: $('#frmCustomer').serialize()+"&_token="+_token,
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        if(data.status == 'success'){
                            $('#addNewSellerModalClose').trigger('click');
                            customerSelect2(data.customer_id, $('#ic_commissioner'));
                        }
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        if(errors.email != undefined){
                            $('#error_email').html(errors.email[0]);
                        }
                    }
                });
            }
        });

    });

    function checkedDisabledFields(obj){
        var chk_id = obj.attr('id');
        if(!obj.prop("checked")) {
            $("#txt_"+chk_id).attr("disabled", "disabled");
        }
        if(obj.prop("checked")){
            $("#txt_"+chk_id).removeAttr("disabled");
        }
    }

    function checkedIcDetails(obj){
        console.log('ic_details : ',obj.prop("checked"));
        if(!obj.prop("checked")) {
            $(".txt_ic_details").attr("disabled", "disabled");
        }
        if(obj.prop("checked")){
            $(".txt_ic_details").removeAttr("disabled");
            customerSelect2(ic_commissioner, $('#ic_commissioner'));
        }
        
        // if(obj.prop("checked")){
        //     $(".txt_ic_details").removeAttr("disabled");
        //     fnCallbackCommissioner();
        //     if(ic_commissioner > 0){
        //         $("#ic_commissioner").select2().val(ic_commissioner).trigger("change");
        //     }
        // }
    }

    // function fnCallbackCommissioner(init=false){
    //     getSelect2Customer();
    //     var old_val = $('#ic_commissioner').val();
    //     $('#ic_commissioner').val('');
    //     $('#ic_commissioner').select2().empty();
    //     $('#ic_commissioner').select2({placeholder: 'Select', data:sel2customer});
    //     $('#ic_commissioner').select2();
    // }

</script>
@endsection
