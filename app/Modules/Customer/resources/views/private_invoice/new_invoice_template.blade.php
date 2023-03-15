<script id="private_item_template" type="text/x-handlebars-template">
<div class="divPrivateItem" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
    <div class="row">
        <input type="hidden" name="customer_item_id[]" id="customer_item_id" value="@{{id}}">

        <div class="form-group col-md-5">
            <label class="form-control-label">{{ __('Item') }}</label>
            <select name="item_id[]" class="form-control selectpicker item_id select2" required >
                @{{#each private_items as |value key|}}
                    @{{#if_eq key ../item_id}}
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
            <button type="button" class="close" aria-label="Close" id='removeButtonPrivate'>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-5">
            <label class="form-control-label">{{ __('Buyer Premium (%)') }}</label>
            <input type="text" class="form-control buyer_premiun" id="buyer_premiun" name="buyer_premiun[]" value="" data-parsley-type="number" required />
        </div>
    </div>
</div>
</script>