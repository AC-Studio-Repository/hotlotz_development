<script id="category_property_template" type="text/x-handlebars-template">
<div class="divCatProperty" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
    <div class="form-row">
        <div class="form-group col-md-4">
            <label class="form-control-label">{{ __('Properties') }}</label>
            <input type="hidden" name="property_id[]" id="property_id" value="@{{id}}">
            <input type="text" class="form-control" name="key[]" value="@{{key}}" placeholder="Properties" required />
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Property Values') }}</label>
            <input type="text" class="form-control property_value" id="property_value" name="value[]" value="@{{value}}" placeholder="Property Values" />
        </div>
        <div class="form-group col-md-2 text-right">
            <!-- <button type="button" class="btn btn-danger" id='removeButton'><i class="zmdi zmdi-close"></i></button> -->

            <button type="button" class="close" aria-label="Close" id='removeButton'>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label class="form-control-label">{{ __('Field Type') }}</label>
            <select name="field_type[]" class="form-control selectpicker field_type">
                @{{#each field_types as |value key|}}
                    @{{#if_eq key ../field_type}}
                        <option value="@{{key}}" selected="selected">@{{value}}</option>
                    @{{else}}
                        <option value="@{{key}}">@{{value}}</option>
                    @{{/if_eq}}
                @{{/each}}
            </select>
        </div>
        <div class="form-group col-md-4">
            <label class="form-control-label">{{ __('Required/optional') }}</label>
            <div class="radio-inline">
                <select name="is_required[]" class="form-control selectpicker is_required">
                    @{{#each req_lists as |value key|}}
                        @{{#if_eq key ../is_required}}
                            <option value="@{{key}}" selected="selected">@{{value}}</option>
                        @{{else}}
                            <option value="@{{key}}">@{{value}}</option>
                        @{{/if_eq}}
                    @{{/each}}
                </select>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label class="form-control-label">{{ __('Filter') }}</label>
            <div class="radio-inline">
                <select name="is_filter[]" class="form-control selectpicker is_filter">
                    @{{#each filter_lists as |value key|}}
                        @{{#if_eq key ../is_filter}}
                            <option value="@{{key}}" selected="selected">@{{value}}</option>
                        @{{else}}
                            <option value="@{{key}}">@{{value}}</option>
                        @{{/if_eq}}
                    @{{/each}}
                </select>
            </div>
        </div>
    </div>
</div>
</script>