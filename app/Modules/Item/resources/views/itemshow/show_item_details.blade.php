<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Cataloguer') }} <span style="color:red">*</span></label>
        {{ Form::select('cataloguer_id', $cataloguers, old('cataloguer_id', isset($item->cataloguer_id)? (integer)$item->cataloguer_id:null), [
                'class'=>'form-control',
                'id' => 'cataloguer_id',
                'disabled',
            ])
        }}
    </div>


    @can('item approve')
    <!-- Add an option for the cataloguing to approved by someone once someone has finished and be able to filter by items which need approval //Filter "Approval Needed" -->
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Approver') }}</label>
        {{ Form::select('cataloguing_approver_id', $approvers, ($item->cataloguing_approver_id > 0)? (integer)$item->cataloguing_approver_id:$user_id, [
                'class'=>'form-control',
                'id' => 'cataloguing_approver_id',
                (isset($item->cataloguing_approver_id) && $item->cataloguing_approver_id > 0)?'disabled':null,
            ])
        }}

        @if ($errors->has('cataloguing_approver_id'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('cataloguing_approver_id') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4" id="divApproveButton">
        <label class="form-control-label">&nbsp;</label>
        @if( $item->is_cataloguing_approved != 'Y' )
            <div>
                <button type="button" class="btn btn-primary" id="btnCataloguingApprove">{{ __('Approve') }}</button>
            </div>
        @endif
    </div>
    @endcan
</div>

<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="is_new">
                {{ Form::checkbox('is_new', 'Y', ($item->is_new == 'Y')?'checked':null, [
                        'id' => "is_new",
                        'disabled'
                    ])
                }}
                New Item
                &nbsp;
            </label>
        </div>
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="is_tree_planted">
                {{ Form::checkbox('is_tree_planted', 'Y', ($item->is_tree_planted == 'Y')?'checked':'', [
                        'id' => "is_tree_planted",
                        'disabled'
                    ])
                }}
                One Tree Planted
                &nbsp;
            </label>
        </div>
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="is_highlight">
                {{ Form::checkbox('is_highlight', 'Y', ($item->is_highlight == 'Y')?'checked':'', [
                        'id' => "is_highlight",
                        'disabled'
                    ])
                }}
                Marketplace Highlight
                &nbsp;
            </label>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Seller') }} <span style="color:red">*</span></label>
        {{ Form::text('customer_id', isset($item->customer) ? ($item->customer->ref_no.'_'.$item->customer->select2_fullname) : null, [
                'class'=>'form-control',
                'disabled',
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Item Status') }}</label>
        {{ Form::text('status', isset($item->status)?$item->status:null, [
                'class'=>'form-control',
                'id'=>'status',
                'disabled',
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Permission to sell') }}</label>
        {{ Form::text('permission_to_sell', ($item->permission_to_sell == 'Y')?'Yes':'No', [
                'class'=>'form-control',
                'disabled',
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Item Name') }} <span style="color:red;">*</span></label>
        {{ Form::text('name', isset($item->name)?$item->name:null, [
                'class' => 'form-control',
                'disabled',
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Brand') }}</label>
        {{ Form::text('brand', isset($item->brand)?$item->brand:null, [
                'class' => 'form-control',
                'disabled',
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Item Number') }}</label>
        {{ Form::text('item_code', isset($item->item_number)?$item->item_number:null, [
                'class' => 'form-control',
                'disabled',
            ])
        }}        
        <div class="font-sm" style="color:red;">Note: The item number may vary upon item save depending on the availability of the item number.</div>
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Location') }}</label>
        {{ Form::text('location', isset($item->location)?$item->location:null, [
                'class'=>'form-control',
                'disabled',
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Description') }}</label>
        {{ Form::textarea('long_description',  isset($item->long_description)?$item->long_description:null,
            [
                'class' => 'form-control',
                'disabled',
                'rows' => 10,
            ]
        ) }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Category') }}</label>
        {{ Form::text('category_id', isset($item->category_id)?$item->category->name:null, [
                'class'=>'form-control',
                'disabled',
            ])
        }}
    </div>

    @if($item->category_id != 13)
    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">{{ __('Sub Category') }}</label>
        {{ Form::text('sub_category', isset($item->sub_category)?$item->sub_category:null, [
                'class' => 'form-control',
                'disabled',
            ])
        }}
    </div>
    @endif

    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="is_pspm">
                {{ Form::checkbox('is_pspm', 'Y', ($item->is_pspm == 'Y')?'checked':null, [
                        'id' => "is_pspm",
                        'disabled'
                    ])
                }}
                {{ __('Precious Stone, Precious Metal') }}
                &nbsp;
            </label>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Condition') }}</label>
        {{ Form::select('condition', $conditions, $item->condition, [
                'class' => 'form-control',
                'disabled',
            ])
        }}
    </div>
    @if($item->condition == 'specific_condition' || $item->condition == 'general_condition')
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">&nbsp;</label>
        {{ Form::textarea('specific_condition_value', $item->specific_condition_value, [
                'class' => 'form-control',
                'id'=>'specific_condition_value',
                'rows' => 15,
                'disabled',
            ])
        }}
    </div>
    @endif
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Dimensions') }}</label>
        {{ Form::text('dimensions',  isset($item->dimensions)?$item->dimensions:null, [
                'class' => 'form-control',
                'disabled',
                'rows' => 5,
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Weight') }}</label>
        {{ Form::text('weight', isset($item->weight)?$item->weight:null, [
                'class'=>'form-control',
                'disabled',
                'rows' => 5,
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Provenance') }}</label>
        {{ Form::text('provenance',  isset($item->provenance)?$item->provenance:null, [
                'class'=>'form-control',
                'disabled',
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Designation') }}</label>
        {{ Form::text('designation',  isset($item->designation)?$item->designation:null, [
                'class'=>'form-control',
                'disabled',
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Additional Notes') }}</label>
        {{ Form::textarea('additional_notes',  isset($item->additional_notes)?$item->additional_notes:null,
            [
                'class' => 'form-control',
                'rows' => 10,
                'disabled',
            ]
        ) }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Internal Notes') }}</label>
        {{ Form::textarea('internal_notes',  isset($item->internal_notes)?$item->internal_notes:null,
            [
                'class' => 'form-control',
                'rows' => 10,
                'disabled',
            ]
        ) }}
    </div>
</div>

@if($item->category_id != 13)
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Category Properties') }}</label>
        <div id="divCategoryProperty" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">
            @include('item::itemshow.show_category_property', array('categoryproperties'=>$categoryproperties))
        </div>
    </div>
</div>
@endif

<div class="form-group col-12 col-md-6 col-xl-6">
    <label class="form-control-label">&nbsp;</label>
    <div class="input-group">
        <label class="checkbox-inline" for="is_pro_photo_need">
            {{ Form::checkbox('is_pro_photo_need', 'Y', ($item->is_pro_photo_need == 'Y')?'checked':null, [
                    'id' => "is_pro_photo_need",
                    'disabled'
                ])
            }}
            Professional photography needed
            &nbsp;
        </label>
    </div>
</div>

<!-- Item Image -->
<div class="row">
    <label class="form-control-label col-12 col-md-12 col-xl-12">{{ __('Item Image') }} (576px x 430px) <span style="color:red;">*</span></label>
    <div class="form-group col-12 col-md-12 col-xl-12">
        @foreach($item_images as $itemimage)
            <label style="width: 400px; height: 300px; text-align: center;">
                <img onclick="imagepreview(this)" lazyload="on" src="{{ $itemimage->full_path }}" alt="{{ $itemimage->file_name }}" style="width:auto;height:auto;max-width:100%;max-height:100%;">
            </label>
        @endforeach
    </div>
</div>

<!-- Item Video -->
@if(count($item_videos) > 0)
<div class="row">
    <label class="form-control-label col-12 col-md-12 col-xl-12">{{ __('Item Video') }}</label>
    <div class="form-group col-12 col-md-12 col-xl-12">
        @foreach($item_videos as $itemvideo)
            <label style="width: 300px; height: 200px; text-align: center;">
                <video width="300px" height="200px" controls>
                    <source src="{{ $itemvideo->full_path }}" type=video/mp4>
                </video>
            </label>
        @endforeach
    </div>
</div>
@endif

<!-- Item Internal Photo -->
@if(count($item_internal_photos) > 0)
<div class="row">
    <label class="form-control-label col-12 col-md-12 col-xl-12">{{ __('Item Internal Photo') }}</label>
    <div class="form-group col-12 col-md-12 col-xl-12">
        @foreach($item_internal_photos as $item_internalphoto)
            <label style="width: 400px; height: 300px; text-align: center;">
                <img onclick="imagepreview(this)" lazyload="on" src="{{ $item_internalphoto->full_path }}" alt="{{ $item_internalphoto->file_name }}" style="width:auto;height:auto;max-width:100%;max-height:100%;">
            </label>
        @endforeach
    </div>
</div>
@endif