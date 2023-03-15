<script id="auction_template" type="text/x-handlebars-template">
	<div class="divLifecycle2" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
		<input type="hidden" name="item_lifecycle_id[]" class="item_lifecycle_id" value="@{{id}}" @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>
		<input type="hidden" name="type[]" id="type" value="auction" @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>
		<input type="hidden" name="period[]" id="period" value="" @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>        
        

		<div class="form-row divAuction">
		    <label class="form-control-label col-md-3">{{ __('Auction') }} <span style="color: red;">*</span></label>
		    <div class="form-group col-6 col-md-6 col-xl-6">
	            <select name="auction_id[]" class="form-control selectpicker auction_id" required @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>
	                @{{#each auctions as |value key|}}
	                    @{{#if_eq key ../reference_id}}
	                        <option value="@{{key}}" selected="selected">@{{value}}</option>
	                    @{{else}}
	                        <option value="@{{key}}">@{{value}}</option>
	                    @{{/if_eq}}
	                @{{/each}}
	            </select>
		    </div>
		</div>

		<div class="form-row">
		    <label class="form-control-label col-md-3">{{ __('Opening Bid') }} <span style="color: red;">*</span></label>
		    <div class="form-group col-6 col-md-6 col-xl-6">
                <div class="input-group">
                    <input type="text" name="price[]" value="@{{price}}" class="form-control" required data-parsley-type="number" data-parsley-trigger="keyup" min="80" data-parsley-errors-container='#error_auciton_price' @{{#if_not_eq status null}} disabled @{{/if_not_eq}} >
                    <span class="input-group-addon">SGD</span>
                </div>
                <div id='error_auciton_price'></div>
		    </div>
		</div>
	</div>
</script>

<script id="mp_template" type="text/x-handlebars-template">
    <div class="divLifecycle2" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
    	<input type="hidden" name="item_lifecycle_id[]" class="item_lifecycle_id" value="@{{id}}" @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>
        <input type="hidden" name="type[]" id="type" value="marketplace" @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>

        <div class="form-row">
            <label class="form-control-label col-md-3">{{ __('Marketplace Price') }} <span style="color: red;">*</span></label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <div class="input-group">
                    <input type="text" name="price[]" value="@{{price}}" class="form-control" required data-parsley-type="number" data-parsley-trigger="keyup" min="80" data-parsley-errors-container='#error_mp_price' @{{#if_not_eq status null}} disabled @{{/if_not_eq}} >
                    <span class="input-group-addon">SGD</span>
                </div>
                <div id='error_mp_price'></div>
            </div>
        </div>
            
        <div class="form-row">
            <label class="form-control-label col-md-3">{{ __('Period') }} <span style="color: red;">*</span></label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <div class="input-group">
                    <input type="text" name="period[]" value="@{{period}}" class="form-control" required data-parsley-errors-container='#error_mp_period' @{{#if_not_eq status null}} disabled @{{/if_not_eq}} >
                    <span class="input-group-addon">Days</span>
                </div>
                <div id='error_mp_period'></div>
            </div>
        </div>
    </div>
</script>

<script id="clear_template" type="text/x-handlebars-template">
    <div class="divLifecycle2" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
        <input type="hidden" name="item_lifecycle_id[]" class="item_lifecycle_id" value="@{{id}}" @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>
        <input type="hidden" name="type[]" id="type" value="clearance" @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>

        <div class="form-row">
            <label class="form-control-label col-md-3">{{ __('Clearance Price') }} <span style="color: red;">*</span></label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <div class="input-group">
                    <input type="text" name="price[]" value="@{{price}}" class="form-control" required data-parsley-type="number" data-parsley-trigger="keyup" min="80" data-parsley-errors-container='#error_cl_price' @{{#if_not_eq status null}} disabled @{{/if_not_eq}} >
                    <span class="input-group-addon">SGD</span>
                </div>
                <div id='error_cl_price'></div>
            </div>
        </div>
            
        <div class="form-row">
            <label class="form-control-label col-md-3">{{ __('Period') }} <span style="color: red;">*</span></label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <div class="input-group">
                    <input type="text" name="period[]" value="@{{period}}" class="form-control" required data-parsley-errors-container='#error_cl_period' @{{#if_not_eq status null}} disabled @{{/if_not_eq}} >
                    <span class="input-group-addon">Days</span>
                </div>
				<div id='error_cl_period'></div>
            </div>
        </div>
    </div>
</script>

<script id="privatesale_template" type="text/x-handlebars-template">
    <div class="divLifecycle2" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
        <input type="hidden" name="item_lifecycle_id[]" class="item_lifecycle_id" value="@{{id}}" @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>
        <input type="hidden" name="type[]" id="type" value="privatesale" @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>

        <div class="form-row">
            <label class="form-control-label col-md-3">{{ __('Private Sale Price') }} <span style="color: red;">*</span></label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <div class="input-group">
                    <input type="text" name="price[]" value="@{{price}}" class="form-control" required data-parsley-errors-container='#error_ps_price' @{{#if_not_eq status null}} disabled @{{/if_not_eq}} >
                    <span class="input-group-addon">SGD</span>
                </div>
                <div id='error_ps_price'></div>
            </div>
        </div>
            
        <div class="form-row">
            <label class="form-control-label col-md-3">{{ __('Period') }} <span style="color: red;">*</span></label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <div class="input-group">
                    <input type="text" name="period[]" value="@{{period}}" class="form-control" required data-parsley-errors-container='#error_ps_period' @{{#if_not_eq status null}} disabled @{{/if_not_eq}} >
                    <span class="input-group-addon">Days</span>
                </div>
                <div id='error_ps_period'></div>
            </div>
        </div>
    </div>
</script>

<script id="storage_template" type="text/x-handlebars-template">
    <div class="divLifecycle2" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
        <input type="hidden" name="item_lifecycle_id[]" class="item_lifecycle_id" value="@{{id}}" @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>
        <input type="hidden" name="type[]" id="type" value="storage" @{{#if_not_eq status null}} disabled @{{/if_not_eq}}>

        <div class="form-row">
            <label class="form-control-label col-md-3">{{ __('Storage Price') }} <span style="color: red;">*</span></label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <div class="input-group">
                    <input type="text" name="price[]" value="@{{price}}" class="form-control" required data-parsley-errors-container='#error_storage_price' @{{#if_not_eq status null}} disabled @{{/if_not_eq}} @{{#if_eq lifecycle_id '13'}} disabled @{{/if_eq}} >
                    <span class="input-group-addon">SGD/Day</span>
                </div>
                <div id='error_storage_price'></div>
            </div>
        </div>
            
        <div class="form-row">
            <label class="form-control-label col-md-3">{{ __('Storage First Period') }} <span style="color: red;">*</span></label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <div class="input-group">
                    <input type="text" name="period[]" value="@{{period}}" class="form-control" required data-parsley-errors-container='#error_storage_period' @{{#if_not_eq status null}} disabled @{{/if_not_eq}} @{{#if_eq lifecycle_id '13'}} disabled @{{/if_eq}} >
                    <span class="input-group-addon">Days</span>
                </div>
                <div id='error_storage_period'></div>
            </div>
        </div>
            
        <div class="form-row">
            <label class="form-control-label col-md-3">{{ __('Storage Second Period') }} <span style="color: red;">*</span></label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <div class="input-group">
                    <input type="text" name="second_period" value="@{{second_period}}" class="form-control" required data-parsley-errors-container='#error_storage_second_period' @{{#if_not_eq status null}} disabled @{{/if_not_eq}} @{{#if_eq lifecycle_id 13}} disabled @{{/if_eq}} >
                    <span class="input-group-addon">Days</span>
                </div>
                <div id='error_storage_second_period'></div>
            </div>
        </div>
        <div class="form-row">
            <label class="form-control-label col-md-3" for="is_indefinite_period">{{ __('Storage Period Indefinite') }}</label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <input type="checkbox" id="is_indefinite_period" name="is_indefinite_period" value="Y" @{{#if_eq is_indefinite_period 'Y'}} checked="checked" @{{/if_eq}}data-parsley-errors-container='#error_is_indefinite_period' @{{#if_not_eq status null}} disabled @{{/if_not_eq}} @{{#if_eq lifecycle_id '13'}} disabled @{{/if_eq}} >
            </div>
            <div id='error_is_indefinite_period'></div>
        </div>
    </div>
</script>