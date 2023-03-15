<div class="form-row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Main Title') }} <span style="color:red;">*</span></label>
        {{ Form::text('title', null, [
                'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
                'required'
            ])
        }}

        @if ($errors->has('title'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Description') }} <span style="color:red;">*</span></label>
        {{ Form::textarea('description', null, [
                'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                'rows' => 5,
                'required'
            ])
        }}

        @if ($errors->has('description'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Link') }}</label>
        {{ Form::text('link', null, [
                'class' => 'form-control form-control-md' . ($errors->has('link') ? ' is-invalid' : ''),
            ])
        }}

        @if ($errors->has('link'))
            <div class="invalid-feedback">{{ $errors->first('link') }}</div>
        @endif
    </div>
</div>