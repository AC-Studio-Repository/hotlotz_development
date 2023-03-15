<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Category Name') }}</label>
        {{ Form::select('category_id', $categories, null, [
                'class'=>'form-control' . ($errors->has('category_id') ? ' is-invalid' : ''),
                'id'=>'category_id',
            ])
        }}

        @if ($errors->has('category_id'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('category_id') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Sub Category') }}</label>
        {{ Form::select('sub_category', [], old('sub_category', isset($item->sub_category)?$item->sub_category:null), [
                'class' => 'form-control' . ($errors->has('sub_category') ? ' is-invalid' : ''),
                'id'=>'sub_category',
                'data-parsley-required'=>'false',
                'data-parsley-required-message'=>"This value is required.",
            ])
        }}

        @if ($errors->has('sub_category'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('sub_category') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Category Properties') }}</label>
        <div id="divCategoryProperty" style="border: 2px solid #ddd; padding: 20px; margin-bottom: 5px;">            
            @include('item::itemdetails._category_property', array('categoryproperties'=>[]))
        </div>
    </div>
</div>