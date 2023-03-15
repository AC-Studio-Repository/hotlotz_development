<script id="custom_lifecycle_template" type="text/x-handlebars-template">
<div class="divLifecycle" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
    @{{#if_not_eq status 'Finished'}}
        <div class="row">
            <div class="form-group col-md-12 text-right">
                <button type="button" class="btn btn-default" id='removeButton'>
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    @{{/if_not_eq}}

    <div class="form-row">
        <label class="form-control-label col-md-2">{{ __('Type') }}</label>
        <div class="form-group col-6 col-md-6 col-xl-6">
            <select name="type[]" class="form-control selectpicker type" @{{#if_not_eq status null}} disabled="true" @{{/if_not_eq}}>
                @{{#each lifecycle_types as |value key|}}
                    @{{#if_eq key ../type}}
                        <option value="@{{key}}" selected="selected">@{{value}}</option>
                    @{{else}}
                        <option value="@{{key}}">@{{value}}</option>
                    @{{/if_eq}}
                @{{/each}}
            </select>
        </div>
    </div>

    <div class="form-row">
        <label class="form-control-label col-md-2">{{ __('Initial Price') }}</label>
        <div class="form-group col-6 col-md-6 col-xl-6">
            <input type="hidden" name="item_lifecycle_id[]" id="item_lifecycle_id" value="@{{id}}">
            <input type="text" name="price[]" value="@{{price}}" class="form-control" @{{#if_not_eq status null}} disabled="true" @{{/if_not_eq}}>
        </div>
    </div>

    <div class="form-row divAuction">
        <label class="form-control-label col-md-2">{{ __('Auction') }}</label>
        <div class="form-group col-6 col-md-6 col-xl-6">
            <select name="auction_id[]" class="form-control selectpicker" @{{#if_not_eq status null}} disabled="true" @{{/if_not_eq}}>
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

    <div class="divMarketplace">
        <div class="form-row">
            <label class="form-control-label col-md-2">{{ __('Marketplace') }}</label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <input type="hidden" name="hid_marketplace[]" class="hid_marketplace" value="@{{hid_marketplace}}">

                <label class="checkbox-inline" for="">

                    <input type="checkbox" name="marketplace[]" value="marketplace" class="type_mp" @{{#ifInArray 'marketplace' reference_id}} checked="checked" @{{/ifInArray}} @{{#if_not_eq status null}} disabled="true" @{{/if_not_eq}}>
                    {{ __('Marketplace') }}
                    &nbsp;

                </label>

                <label class="checkbox-inline" for="">
                    
                    <input type="checkbox" name="marketplace[]" value="clearance" class="type_cle" @{{#ifInArray 'clearance' reference_id}} checked="checked" @{{/ifInArray}} @{{#if_not_eq status null}} disabled="true" @{{/if_not_eq}}>
                    {{ __('Clearance') }}
                    &nbsp;

                </label>
            </div>
        </div>    

        <div class="form-row">
            <label class="form-control-label col-md-2">{{ __('Period') }}</label>
            <div class="form-group col-6 col-md-6 col-xl-6">
                <div class="input-group">
                    <input type="text" name="period[]" value="@{{period}}" class="form-control" @{{#if_not_eq status null}} disabled="true" @{{/if_not_eq}} >
                    <span class="input-group-addon">Days</span>
                </div>
            </div>
        </div>
    </div>
</div>
</script>