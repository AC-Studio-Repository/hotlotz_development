<script id="xero_item_template" type="text/x-handlebars-template">
<div class="divXeroItem" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
    <div class="row">
        <input type="hidden" name="customer_xero_item_id[]" id="customer_xero_item_id" value="@{{id}}">

        <div class="form-group col-md-5">
            <label class="form-control-label">{{ __('Xero Item') }}</label>
            <select name="xero_item_id[]" class="form-control selectpicker xero_item_id" required >
                @{{#each xero_items as |value key|}}
                    @{{#if_eq key ../xero_item_id}}
                        <option value="@{{key}}" selected="selected">@{{value}}</option>
                    @{{else}}
                        <option value="@{{key}}">@{{value}}</option>
                    @{{/if_eq}}
                @{{/each}}
            </select>
        </div>
        
        <div class="form-group col-md-5">
            <label class="form-control-label">{{ __('Price') }}</label>
            <input type="text" class="form-control price" id="price" name="price[]" value="@{{price}}" data-parsley-type="number" required />
        </div>

        <div class="form-group col-md-2 text-right">
            <button type="button" class="close" aria-label="Close" id='removeButton'>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group col-md-12">
            <label class="form-control-label">{{ __('Notes') }}</label>
            <div class="radio-inline">
                <textarea name="notes[]" class="form-control notes" id="notes" rows="5">@{{notes}}</textarea>
            </div>
        </div>
    </div>
</div>
</script>