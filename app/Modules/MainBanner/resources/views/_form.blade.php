<div class="form-row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Main Title') }}</label>
        {{ Form::text('main_title', null, [
                'class' => 'form-control' . ($errors->has('main_title') ? ' is-invalid' : ''),
            ])
        }}

        @if ($errors->has('main_title'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('main_title') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Sub Title') }}</label>
        {{ Form::text('sub_title', null, [
                'class' => 'form-control' . ($errors->has('sub_title') ? ' is-invalid' : ''),
            ])
        }}

        @if ($errors->has('sub_title'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('sub_title') }}</div>
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

<div class="row">
    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Position') }}</label>
        <div class="form-group">
            <label class="radio-inline" for="position_left">
                {{ Form::radio('position', 'left', true, ['class' => 'form-control position', 'id'=>'position_left', 'data-parsley-errors-container'=>"#error_position"]) }}
                Left
                &nbsp;
            </label>
            <label class="radio-inline" for="position_right">
                {{ Form::radio('position', 'right', false, ['class' => 'form-control position', 'id'=>'position_right', 'data-parsley-errors-container'=>"#error_position"]) }}
                Right
                &nbsp;
            </label>
            <label class="radio-inline" for="position_hide">
                {{ Form::radio('position', 'hide', false, ['class' => 'form-control position', 'id'=>'position_hide', 'data-parsley-errors-container'=>"#error_position"]) }}
                Hide
                &nbsp;
            </label>
        </div>
        <div id='error_position'></div>

        @if ($errors->has('position'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('position') }}</div>
        @endif
    </div>

    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Color') }}</label>
        <div class="form-group">
            <label class="radio-inline" for="color_navy" style="margin-right: 5px !important;">
                {{ Form::radio('color', 'navy', true, ['class' => 'form-control color', 'id'=>'color_navy', 'data-parsley-errors-container'=>"#error_color"]) }}
                <div style="background-color: #0E143E; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                &nbsp;
            </label>
            <label class="radio-inline" for="color_pink" style="margin-right: 5px !important;">
                {{ Form::radio('color', 'pink', false, ['class' => 'form-control color', 'id'=>'color_pink', 'data-parsley-errors-container'=>"#error_color"]) }}
                <div style="background-color: #E70C3B; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                &nbsp;
            </label>
            <label class="radio-inline" for="color_turquoise" style="margin-right: 5px !important;">
                {{ Form::radio('color', 'turquoise', false, ['class' => 'form-control color', 'id'=>'color_turquoise', 'data-parsley-errors-container'=>"#error_color"]) }}
                <div style="background-color: #61C4DD; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                &nbsp;
            </label>
        </div>
        <div id='error_color'></div>

        @if ($errors->has('color'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('color') }}</div>
        @endif
    </div>
</div>

<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label>{{ __('Banner Image') }} (1920px x 1000px) </label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="banner_image" id="image_input" value="{{ old('banner_image',isset($main_banner->full_path)?$main_banner->full_path:'') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this);" data-parsley-errors-container='#error_image_block' />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#image_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ isset($main_banner->full_path)?$main_banner->full_path:'' }}" class="img-responsive" width="895px" height="240px">
            <div id="error_image_block"></div>
        </div>
    </div>
</div>