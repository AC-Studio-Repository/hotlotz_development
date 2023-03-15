<div class="form-row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Parent Category') }}</label>
        {{ Form::select('parent_id', $parent_categories, $category->parent_id, array('class'=>'form-control' . ($errors->has('parent_id') ? ' is-invalid' : '') ))}}

        @if ($errors->has('parent_id'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('parent_id') }}</div>
        @endif
    </div>
</div>
<div class="form-row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Category Name*') }}</label>
        {{ Form::text('name', null, [
                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                'placeholder' => __('Category Name')
            ])
        }}

        @if ($errors->has('name'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
        @endif
    </div>
</div>