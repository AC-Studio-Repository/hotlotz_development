<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label>{{ __('Highlight Image') }} (576px x 576px) <span style="color:red">*</span> </label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="highlight_image" id="highlight_image_input" value="{{ old('highlight_image', $highlight->full_path ?? '') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this,'#highlight_image_preview');" data-parsley-errors-container='#error_image_block' {{ !isset($highlight->full_path)?'required':null }} />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#highlight_image_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="highlight_image_preview" src="{{ $highlight->full_path ?? '' }}" class="img-responsive" width="300" height="300">
            <div id="error_image_block"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Title') }} <span style="color:red">*</span></label>
        {{ Form::text('title', old('title', $highlight->title ?? ''), [
                'class' => 'form-control form-control-md' . ($errors->has('title') ? ' is-invalid' : ''),
                'required'
            ])
        }}

        @if ($errors->has('title'))
            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Price') }}</label>
        {{ Form::text('description', old('description', $highlight->description ?? ''), [
                'class' => 'form-control form-control-md' . ($errors->has('description') ? ' is-invalid' : ''),
            ])
        }}

        @if ($errors->has('description'))
            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
        @endif
    </div>
</div>