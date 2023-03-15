<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Name') }}</label>
        <div class="form-group">
            {{ Form::text('name', $our_team->name, [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Image One') }} (576px x 576px)</label>
        <div class="form-group">            
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ $our_team->full_path ?? '' }}" class="img-responsive" width="300px" height="300px">
        </div>
    </div>

    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Image Two') }} (576px x 576px)</label>
        <div class="form-group">            
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ $our_team->full_path2 ?? '' }}" class="img-responsive" width="300px" height="300px">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="form-control-label">{{ __('Position') }}</label>
        <div class="form-group">
            <label class="form-control disabled" style="background-color: #C9D0D0; opacity: 1; height: 100px">
                {!! $our_team->position !!}
            </label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Email') }}</label>
        <div class="form-group">
            {{ Form::email('contact_email', $our_team->contact_email, [
                    'class' => 'form-control',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Language') }}</label>
        <div class="form-group">
            {{ Form::textarea('motto', $our_team->motto,
                [
                    'class' => 'form-control',
                    'rows' => 3,
                    'disabled',
                ]
            ) }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Experience') }}</label>
        <div class="form-group">
            {{ Form::textarea('experience', $our_team->experience,
                [
                    'class' => 'form-control',
                    'rows' => 3,
                    'disabled',
                ]
            ) }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Fun Fact') }}</label>
        <div class="form-group">
            {{ Form::textarea('fun_fact', $our_team->fun_fact,
                [
                    'class' => 'form-control',
                    'rows' => 3,
                    'disabled',
                ]
            ) }}
        </div>
    </div>
</div>