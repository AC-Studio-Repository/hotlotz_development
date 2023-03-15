<div class="form-row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Category Name') }} <span style="color:red;">*</span></label>
        {{ Form::text('name', null, [
                'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                'placeholder' => __('Category Name'),
                'required'
            ])
        }}

        @if ($errors->has('name'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
        @endif
    </div>
</div>