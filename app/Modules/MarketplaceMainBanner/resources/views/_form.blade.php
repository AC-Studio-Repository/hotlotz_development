<div class="row">
    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Caption') }}</label>
        <div class="form-group">
            {{ Form::text('caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('caption') ? ' is-invalid' : ''),
                    'id' => 'caption',
                ])
            }}

            @if ($errors->has('caption'))
                <div class="invalid-feedback">{{ $errors->first('caption') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Banner') }} (1920px x 556px) <span style="color:red">*</span></label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="banner" id="banner_input" value="{{ old('banner', $marketplace_main_banner->full_path ?? '') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this,'#banner_preview');" data-parsley-errors-container='#error_banner_block' {{ !isset($marketplace_main_banner->full_path)?'required':null }} />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#banner_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="banner_preview" src="{{ $marketplace_main_banner->full_path ?? '' }}" class="img-responsive" width="1020" height="300">
            <div id="error_banner_block"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Learn More') }}</label>
        <div class="form-group">
            {{ Form::text('learn_more', null,
                [
                    'class' => 'form-control' . ($errors->has('learn_more') ? ' is-invalid' : ''),
                    'id' => 'learn_more'
                ]
            ) }}

            @if ($errors->has('learn_more'))
                <div class="invalid-feedback">{{ $errors->first('learn_more') }}</div>
            @endif
        </div>
    </div>
</div>