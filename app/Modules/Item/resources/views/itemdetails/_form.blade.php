<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Cataloguer') }} <span style="color:red">*</span></label>
        {{ Form::select('cataloguer_id', $cataloguers, old('cataloguer_id', isset($item->cataloguer_id)? (integer)$item->cataloguer_id:null), [
                'class'=>'form-control',
                'id' => 'cataloguer_id',
                'required',
            ])
        }}

        @if ($errors->has('cataloguer_id'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('cataloguer_id') }}</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="is_new">
                {{ Form::checkbox('is_new', 'Y', ($item->is_new == 'Y')?'checked':'', [
                        'id' => "is_new",
                    ]) 
                }}
                New Item
                &nbsp;
            </label>
        </div>

        @if ($errors->has('is_new'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('is_new') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="is_tree_planted">
                {{ Form::checkbox('is_tree_planted', 'Y', ($item->is_tree_planted == 'Y')?'checked':'', [
                        'id' => "is_tree_planted",
                    ])
                }}
                One Tree Planted
                &nbsp;
            </label>
        </div>

        @if ($errors->has('is_tree_planted'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('is_tree_planted') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="is_highlight">
                {{ Form::checkbox('is_highlight', 'Y', ($item->is_highlight == 'Y')?'checked':'', [
                        'id' => "is_highlight",
                    ]) 
                }}
                Marketplace Highlight
                &nbsp;
            </label>
        </div>

        @if ($errors->has('is_highlight'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('is_highlight') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Seller') }} <span style="color:red">*</span></label>
        {{ Form::select('customer_id', [], old('customer_id', isset($item->customer_id)?'selected':null), [
                'class'=>'select2 form-control' . ($errors->has('customer_id') ? ' is-invalid' : ''),
                'id'=>'customer_id',
                'required',
                'data-parsley-errors-container' => "#error_customer_id",
            ])
        }}
        <div id="error_customer_id"></div>

        @if ($errors->has('customer_id'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('customer_id') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Item Status') }}</label>
        {{ Form::text('status', isset($item->status)?$item->status:'Pending', [
                'class' => 'form-control' . ($errors->has('status') ? ' is-invalid' : ''),
                'id'=>'status',
                'disabled',
            ])
        }}

        @if ($errors->has('status'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('status') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Permission to sell') }}</label>
        {{ Form::select('permission_to_sell', ['Y'=>'Yes', 'N'=>'No'], old('permission_to_sell', isset($item->permission_to_sell)?$item->permission_to_sell:'N'), [
                'class'=>'form-control' . ($errors->has('permission_to_sell') ? ' is-invalid' : ''),
                'id'=>'permission_to_sell',
                'disabled'
            ])
        }}

        @if ($errors->has('permission_to_sell'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('permission_to_sell') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Item Name') }} <span style="color:red;">*</span></label>
        {{ Form::text('name', old('name', isset($item->name)?$item->name:null), [
                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                'placeholder' => __('Item Name'),
                'id'=>'name',
                'required',
                'maxlength'=>"60"
            ])
        }}

        @if ($errors->has('name'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Brand') }}</label>
        {{ Form::text('brand', isset($item->brand)?$item->brand:null, [
                'class' => 'form-control' . ($errors->has('brand') ? ' is-invalid' : ''),
                'id'=>'brand',
            ])
        }}

        @if ($errors->has('brand'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('brand') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Item Number') }}</label>
        <input type="hidden" name="item_code_id" value="{{ isset($item->item_code_id)?$item->item_code_id:null }}" id="itemcode_id">
        <input type="hidden" name="item_number" value="{{ isset($item->item_number)?$item->item_number:null }}" id="itemnumber">
        {{ Form::text('item_code', isset($item->item_number)?$item->item_number:null, [
                'class' => 'form-control' . ($errors->has('item_code') ? ' is-invalid' : ''),
                'disabled',
                'id' => 'itemcode'
            ])
        }}
        <div class="font-sm" style="color:red;">Note: The item number may vary upon item save depending on the availability of the item number.</div>
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Location') }}</label>
        {{ Form::select('location', $locations, old('location', isset($item->location)?$item->location:null), [
                'class'=>'form-control' . ($errors->has('location') ? ' is-invalid' : ''),
                'id'=>'location',
            ])
        }}

        @if ($errors->has('location'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('location') }}</div>
        @endif
    </div>
</div>

<div class="form-row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Description') }} <span style="color:red;">*</span></label>
        {{ Form::textarea('long_description', old('long_description', isset($item->long_description)?$item->long_description:null),
            [
                'class' => 'form-control' . ($errors->has('long_description') ? ' is-invalid' : ''),
                'placeholder' => __('Type or copy/paste Description here'),
                'rows' => 10,
                'id'=>'long_description',
                'required'
            ]
        ) }}

        @if ($errors->has('long_description'))
            <div class="invalid-feedback">{{ $errors->first('long_description') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Category') }} <span style="color:red;">*</span></label>
        {{ Form::select('category_id', $categories, null, [
                'class'=>'form-control' . ($errors->has('category_id') ? ' is-invalid' : ''),
                'id'=>'category_id',
                'required',
                'data-parsley-required-message'=>"This value is required.",
            ])
        }}

        @if ($errors->has('category_id'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('category_id') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4 divSubCategory">
        <label class="form-control-label">{{ __('Sub Category') }} <span style="color:red;">*</span></label>
        {{ Form::select('sub_category', [], old('sub_category', isset($item->sub_category)?$item->sub_category:null), [
                'class' => 'form-control' . ($errors->has('sub_category') ? ' is-invalid' : ''),
                'id'=>'sub_category',
                'required',
                'data-parsley-required-message'=>"This value is required.",
            ])
        }}

        @if ($errors->has('sub_category'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('sub_category') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="is_pspm">
                {{ Form::checkbox('is_pspm', 'Y', ($item->is_pspm == 'Y')?'checked':null, [
                        'id' => "is_pspm",
                    ])
                }}
                {{ __('Precious Stone, Precious Metal') }}
                &nbsp;
            </label>
        </div>

        @if ($errors->has('is_pspm'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('is_pspm') }}</div>
        @endif
    </div>
</div>

<div class="row divSubCategoryforOther">
    <div class="form-group col-12 col-md-6 col-xl-6">&nbsp;</div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        {{ Form::text('sub_category_other', null, [
                'class'=>'form-control' . ($errors->has('sub_category_other') ? ' is-invalid' : ''),
                'id'=>'sub_category_other',
            ])
        }}

        @if ($errors->has('sub_category_other'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('sub_category_other') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Condition') }}</label>
        <select name="condition" class="form-control" id="condition">
            @foreach($conditions as $key => $value)
                @php
                    $is_selected = (isset($item->condition) && $item->condition == $key)?"selected":null;
                    $is_disabled = ($key != 'general_condition' && $key != 'specific_condition')?"disabled":null;
                @endphp
                <option value="{{$key}}" {{$is_selected}} {{$is_disabled}}>{{$value}}</option>
            @endforeach
        </select>

        @if ($errors->has('condition'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('condition') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6" id="divSpecificCondition">
        <label class="form-control-label">&nbsp;</label>
        {{ Form::textarea('specific_condition_value', old('specific_condition_value', isset($item->specific_condition_value)?$item->specific_condition_value:$condition_solution), [
                'class' => 'form-control' . ($errors->has('specific_condition_value') ? ' is-invalid' : ''),
                'id'=>'specific_condition_value',
                'rows' => 15,
            ])
        }}

        @if ($errors->has('specific_condition_value'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('specific_condition_value') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Dimensions') }} <span class="dimensions_span" style="color: red;">*</span></label>
        <div class="input-group" style="width: 100%">
            <span class="input-group-addon">
                {{ Form::checkbox('is_dimension', 'Y', (!isset($item->is_dimension) || $item->is_dimension == 'Y')?'checked':'', [
                        'id' => "is_dimension",
                        'data-type' => 'dimensions',
                        'data-type_span' => 'dimensions_span',
                    ]) 
                }}
            </span>
            {{ Form::text('dimensions', null, [
                    'class' => 'form-control' . ($errors->has('dimensions') ? ' is-invalid' : ''),
                    'id'=>'dimensions',
                    'required',
                    "data-parsley-errors-container"=>"#error_dimensions",
                ])
            }}
        </div>
        <div id='error_dimensions'></div>

        @if ($errors->has('dimensions'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('dimensions') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Weight') }} <span class="weight_span" style="color: red;">*</span></label>
        <div class="input-group" style="width: 100%">
            <span class="input-group-addon">
                {{ Form::checkbox('is_weight', 'Y', (!isset($item->is_weight) || $item->is_weight == 'Y')?'checked':'', [
                        'id' => "is_weight",
                        'data-type' => 'weight',
                        'data-type_span' => 'weight_span',
                    ]) 
                }}
            </span>
            {{ Form::text('weight', null, [
                    'class'=>'form-control' . ($errors->has('weight') ? ' is-invalid' : ''),
                    'id'=>'weight',
                    'required',
                    "data-parsley-errors-container"=>"#error_weight",
                ])
            }}
        </div>
        <div id='error_weight'></div>

        @if ($errors->has('weight'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('weight') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Provenance') }}</label>
        {{ Form::text('provenance', old('provenance', isset($item->provenance)?$item->provenance:null), [
                'class'=>'form-control' . ($errors->has('provenance') ? ' is-invalid' : ''),
                'id'=>'provenance',
            ])
        }}

        @if ($errors->has('provenance'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('provenance') }}</div>
        @endif
    </div>

    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Designation') }}</label>
        {{ Form::text('designation', old('designation', isset($item->designation)?$item->designation:null), [
                'class'=>'form-control' . ($errors->has('designation') ? ' is-invalid' : ''),
                'id'=>'designation',
            ])
        }}

        @if ($errors->has('designation'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('designation') }}</div>
        @endif
    </div>
</div>

<div class="form-row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Additional Notes') }}</label>
        {{ Form::textarea('additional_notes', old('additional_notes', isset($item->additional_notes)?$item->additional_notes:null),
            [
                'class' => 'form-control' . ($errors->has('additional_notes') ? ' is-invalid' : ''),
                'rows' => 10,
                'id'=>'additional_notes',
            ]
        ) }}

        @if ($errors->has('additional_notes'))
            <div class="invalid-feedback">{{ $errors->first('additional_notes') }}</div>
        @endif
    </div>
</div>

<div class="form-row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Internal Notes') }}</label>
        {{ Form::textarea('internal_notes', old('internal_notes', isset($item->internal_notes)?$item->internal_notes:null),
            [
                'class' => 'form-control' . ($errors->has('internal_notes') ? ' is-invalid' : ''),
                'rows' => 10,
                'id'=>'internal_notes',
            ]
        ) }}

        @if ($errors->has('internal_notes'))
            <div class="invalid-feedback">{{ $errors->first('internal_notes') }}</div>
        @endif
    </div>
</div>

<div class="row divCategoryPropertyMain">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Category Properties') }}</label>
        <div id="divCategoryProperty" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
            @include('item::itemdetails._category_property', array('categoryproperties'=>[]))
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            @php
                $is_pro_photo_need = 'checked';
                if(isset($item->is_pro_photo_need) && $item->is_pro_photo_need != 'Y'){
                    $is_pro_photo_need = '';
                }
                if(isset($item->is_pro_photo_need) && $item->is_pro_photo_need == 'Y'){
                    $is_pro_photo_need = 'checked';
                }
                if(!isset($item->is_pro_photo_need)){
                    $is_pro_photo_need = 'checked';
                }
            @endphp
            <label class="checkbox-inline" for="is_pro_photo_need">
                {{ Form::checkbox('is_pro_photo_need', 'Y', old('is_pro_photo_need', $is_pro_photo_need), [
                        'id' => "is_pro_photo_need",
                    ]) 
                }}
                Professional photography needed
                &nbsp;
            </label>
        </div>

        @if ($errors->has('is_pro_photo_need'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('is_pro_photo_need') }}</div>
        @endif
    </div>
</div>

<!-- Item Image -->
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Item Image') }} (576px x 430px) <span class="weight_span" style="color: red;">*</span></label>
        <div class="file-loading">
            <input id="item_image" name="item_image[]" type="file" multiple accept="image/*">
        </div>
        
        <input type="text" style="display: none;" name="hide_item_image_ids" id="hide_item_image_ids" value="{{$hide_item_image_ids}}" required data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload at least one Item image!">
        <input type="text" style="display: none;" name="image_reorder" id="image_reorder" value="" >
        <div id="error_uploaded_item_images_block" class="help-block"></div>
    </div>
</div>

<!-- Item Video -->
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Item Video') }}</label>
        <div class="file-loading">
            <input id="item_video" name="item_video[]" type="file" multiple accept="video/*">
        </div>
        
        <input type="text" style="display: none;" name="hide_item_video_ids" id="hide_item_video_ids" value="{{$hide_item_video_ids}}" data-parsley-errors-container="#error_uploaded_item_videos_block" data-parsley-required-message="Please select and upload at least one Item video!">
        <div id="error_uploaded_item_videos_block" class="help-block"></div>
    </div>
</div>

<!-- Item Internal Photo -->
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Item Internal Photo') }}</label>
        <div class="file-loading">
            <input id="item_internal_photo" name="item_internal_photo[]" type="file" multiple accept="image/*">
        </div>
        
        <input type="text" style="display: none;" name="hide_item_internal_photo_ids" id="hide_item_internal_photo_ids" value="{{$hide_item_internal_photo_ids}}" data-parsley-errors-container="#error_uploaded_internal_photos_block" data-parsley-required-message="Please select and upload at least one Item video!">
        <div id="error_uploaded_internal_photos_block" class="help-block"></div>
    </div>
</div>