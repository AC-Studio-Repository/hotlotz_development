<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Name') }} <span style="color:red">*</span></label>
        <div class="form-group">
            {{ Form::text('name', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('name') ? ' is-invalid' : ''),
                    'id' => 'name',
                    'placeholder' => __('Name'),
                    'required'
                ])
            }}

            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Image One') }} (576px x 576px) <span style="color:red">*</span></label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="profile_image" id="profile_image_input" value="{{ old('profile_image', $our_team->full_path ?? '') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this,'#profile_image_preview');" data-parsley-errors-container='#error_profile_image_block' {{ !isset($our_team->full_path)?'required':null }} />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#profile_image_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="profile_image_preview" src="{{ $our_team->full_path ?? '' }}" class="img-responsive" width="300" height="auto">
            <div id="error_profile_image_block"></div>
        </div>
    </div>

    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Image Two') }} (576px x 576px)</label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="profile_image2" id="profile_image2_input" value="{{ old('profile_image2', $our_team->full_path2 ?? '') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this,'#profile_image2_preview');" data-parsley-errors-container='#error_profile_image2_block' />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#profile_image2_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="profile_image2_preview" src="{{ $our_team->full_path2 ?? '' }}" class="img-responsive" width="300" height="auto">
            <div id="error_profile_image2_block"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="form-control-label">{{ __('Position') }}</label>
        <div class="form-group">
            {{ Form::textarea('position', null,
                [
                    'class' => 'form-control' . ($errors->has('position') ? ' is-invalid' : ''),
                    'placeholder' => __('Position'),
                    'rows' => 3,
                    'id' => 'summernote'
                ]
            ) }}

            @if ($errors->has('position'))
                <div class="invalid-feedback">{{ $errors->first('position') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Email') }}</label>
        <div class="form-group">
            {{ Form::email('contact_email', null, [
                    'class' => 'form-control' . ($errors->has('contact_email') ? ' is-invalid' : ''),
                    'id' => 'contact_email',
                    'placeholder' => __('Email')
                ])
            }}

            @if ($errors->has('contact_email'))
                <div class="invalid-feedback">{{ $errors->first('contact_email') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Language') }}</label>
        <div class="form-group">
            {{ Form::textarea('motto', null,
                [
                    'class' => 'form-control' . ($errors->has('motto') ? ' is-invalid' : ''),
                    'placeholder' => __('Language'),
                    'rows' => 3,
                ]
            ) }}

            @if ($errors->has('motto'))
                <div class="invalid-feedback">{{ $errors->first('Language') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Experience') }}</label>
        <div class="form-group">
            {{ Form::textarea('experience', null,
                [
                    'class' => 'form-control' . ($errors->has('experience') ? ' is-invalid' : ''),
                    'placeholder' => __('Experience'),
                    'rows' => 3,
                ]
            ) }}

            @if ($errors->has('experience'))
                <div class="invalid-feedback">{{ $errors->first('Experience') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Fun Fact') }}</label>
        <div class="form-group">
            {{ Form::textarea('fun_fact', null,
                [
                    'class' => 'form-control' . ($errors->has('fun_fact') ? ' is-invalid' : ''),
                    'placeholder' => __('Fun Fact'),
                    'rows' => 3,
                ]
            ) }}

            @if ($errors->has('fun_fact'))
                <div class="invalid-feedback">{{ $errors->first('Fun Fact') }}</div>
            @endif
        </div>
    </div>
</div>