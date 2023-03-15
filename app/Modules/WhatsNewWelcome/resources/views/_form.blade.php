<div class="form-row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Title') }} <span style="color:red;">*</span></label>
        {{ Form::text('title', old('title', $whats_new_welcome->title ?? null), [
                'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
                'required'
            ])
        }}

        @if ($errors->has('title'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Link') }} <span style="color:red;">*</span></label>
        {{ Form::text('link', old('link', $whats_new_welcome->link ?? null), [
                'class' => 'form-control form-control-md' . ($errors->has('link') ? ' is-invalid' : ''),
                'required'
            ])
        }}

        @if ($errors->has('link'))
            <div class="invalid-feedback">{{ $errors->first('link') }}</div>
        @endif
    </div>
</div>
<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label>{{ __('Image') }} (576px x 412px) <span style="color:red;">*</span> </label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="welcome_image" id="image_input" value="{{ old('welcome_image',$whats_new_welcome->full_path ?? '') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this);" data-parsley-errors-container='#error_image_block' {{ !isset($whats_new_welcome->full_path)?'required':null }} />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#image_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ isset($whats_new_welcome->full_path)?$whats_new_welcome->full_path:'' }}" class="img-responsive" width="300px" height="215px">
            <div id="error_image_block"></div>
        </div>
    </div>
</div>