<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Title') }}</label>
        {{ Form::text('title', null, [
                'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
            ])
        }}

        @if ($errors->has('title'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Post Date') }}</label>
        {{ Form::text('post_date', null, [
                'class' => 'form-control form-control-md' . ($errors->has('post_date') ? ' is-invalid' : ''),
                'id' => 'datepicker',
                'placeholder'=>'yyyy-mm-dd'
            ])
        }}

        @if ($errors->has('post_date'))
            <div class="invalid-feedback">{{ $errors->first('post_date') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Link Name') }}</label>
        {{ Form::text('link_name', null, [
                'class' => 'form-control form-control-md' . ($errors->has('link_name') ? ' is-invalid' : ''),
            ])
        }}

        @if ($errors->has('link_name'))
            <div class="invalid-feedback">{{ $errors->first('link_name') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
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

<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Image') }} (576px x 430px) </label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="post_image" id="image_input" value="{{ old('post_image', $blog_post->full_path ?? '') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this);" data-parsley-errors-container='#error_image_block' />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#image_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ isset($blog_post->full_path)?$blog_post->full_path:'' }}" class="img-responsive" width="300px" height="225px">
            <div id="error_image_block"></div>
        </div>
    </div>
</div>