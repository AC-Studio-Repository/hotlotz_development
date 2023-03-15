<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label>{{ __('Image') }} (576px x 576px) <span style="color:red">*</span> </label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="main_image" id="main_image_input" value="{{ old('main_image', $what_we_sell->full_path ?? '') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this,'#main_image_preview');" data-parsley-errors-container='#error_image_block' {{ !isset($what_we_sell->full_path)?'required':null }} />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#main_image_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="main_image_preview" src="{{ $what_we_sell->full_path ?? '' }}" class="img-responsive" width="300" height="300">
            <div id="error_image_block"></div>
        </div>
    </div>
</div>
<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label>{{ __('Banner Image') }} (1920px x 480px) <span style="color:red">*</span> </label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="detail_banner_image" id="detail_banner_image_input" value="{{ old('detail_banner_image', $what_we_sell->detail_banner_full_path ?? '') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this,'#detail_banner_image_preview');" data-parsley-errors-container='#error_detail_banner_block' {{ !isset($what_we_sell->detail_banner_full_path)?'required':null }} />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#detail_banner_image_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="detail_banner_image_preview" src="{{ $what_we_sell->detail_banner_full_path ?? '' }}" class="img-responsive" width="1000" height="250">
            <div id="error_detail_banner_block"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Category') }}</label>
        {{ Form::select('category_id', $categories, null, [
                'class' => 'form-control' . ($errors->has('category_id') ? ' is-invalid' : ''),
            ])
        }}

        @if ($errors->has('category_id'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('category_id') }}</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Caption') }} <span style="color:red">*</span></label>
        {{ Form::text('caption', null, [
                'class' => 'form-control' . ($errors->has('caption') ? ' is-invalid' : ''),
                'required'
            ])
        }}

        @if ($errors->has('caption'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('caption') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="price_status">
                {{ Form::checkbox('price_status', 'Y', old('price_status', ($what_we_sell->price_status == 'Y')?true:false), [
                        'id' => 'price_status'
                    ])
                }}
                &nbsp;
                Sold
            </label>
        </div>

        @if ($errors->has('price_status'))
            <div class="invalid-feedback">{{ $errors->first('price_status') }}</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Title') }} <span style="color:red">*</span></label>
        {{ Form::text('title', null, [
                'class' => 'form-control form-control-md' . ($errors->has('title') ? ' is-invalid' : ''),
                'required'
            ])
        }}

        @if ($errors->has('title'))
            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Title Blog') }} <span style="color:red">*</span></label>
        <textarea name="description" id="description" required data-parsley-errors-container='#error_description_block'>{{ $what_we_sell->description }}</textarea>
        <div id="error_description_block"></div>

        @if ($errors->has('description'))
            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
        @endif
    </div>
</div>

<hr>
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Key Contact Detail') }}</label>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Key Contact 1') }}</label>
        {{ Form::select('key_contact_1', [''=>'Select Key Contact']+$our_teams, null, [
                'class' => 'form-control' . ($errors->has('key_contact_1') ? ' is-invalid' : ''),
            ])
        }}

        @if ($errors->has('key_contact_1'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('key_contact_1') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Key Contact 2') }}</label>
        {{ Form::select('key_contact_2', [''=>'Select Key Contact']+$our_teams, null, [
                'class' => 'form-control' . ($errors->has('key_contact_2') ? ' is-invalid' : ''),
            ])
        }}

        @if ($errors->has('key_contact_2'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('key_contact_2') }}</div>
        @endif
    </div>
</div>