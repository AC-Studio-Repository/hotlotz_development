<div class="row">
    <div class="col-md-4">
        <label>{{ __('Name') }}</label>
        <div class="form-group">
            {{ Form::text('name', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('name') ? ' is-invalid' : ''),
                    'id' => 'name',
                    'placeholder' => __('Name')
                ])
            }}

            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Image One') }}</label>
        
        <div class="file-loading">
            <input id="our_team_image" name="our_team_image" type="file" accept="image/*">
        </div>
        
        <input type="text" style="display: none;" name="hide_team_image_ids" id="hide_team_image_ids" value="{{$hide_team_image_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload at least one banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>

        <input type="text" style="display: none;" name="hide_team_full_path_ids" id="hide_team_full_path_ids" value="{{$hide_team_full_path_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload at least one banner image!">
         <span style="color: #a70909;">Please provide Image with dimensions - 576 x  576px.</span>
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Image Two') }}</label>
        
        <div class="file-loading">
            <input id="team_image2" name="team_image2" type="file" accept="image/*">
        </div>
        
        <input type="text" style="display: none;" name="hide_team_image2_ids" id="hide_team_image2_ids" value="{{$hide_team_image2_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload at least one banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>

        <input type="text" style="display: none;" name="hide_team_full_path2_ids" id="hide_team_full_path2_ids" value="{{$hide_team_full_path2_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload at least one banner image!">
         <span style="color: #a70909;">Please provide Image with dimensions - 576 x  576px.</span>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label>{{ __('Position') }}</label>
        {{ Form::textarea('position', null,
            [
                'class' => 'form-control' . ($errors->has('position') ? ' is-invalid' : ''),
                'placeholder' => __('Position'),
                'rows' => 3,
                'data-parsley-required-message'=>"This value is required.",
                'id' => 'summernote'
            ]
        ) }}

        @if ($errors->has('position'))
            <div class="invalid-feedback">{{ $errors->first('position') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label>{{ __('Email') }}</label>
        <div class="form-group">
            {{ Form::text('contact_email', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('contact_email') ? ' is-invalid' : ''),
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
        <label>{{ __('Motto') }}</label>
        <div class="form-group">
            {{ Form::textarea('motto', null,
                [
                    'class' => 'form-control' . ($errors->has('motto') ? ' is-invalid' : ''),
                    'placeholder' => __('motto'),
                    'rows' => 3,
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ]
            ) }}

            @if ($errors->has('motto'))
                <div class="invalid-feedback">{{ $errors->first('Motto') }}</div>
            @endif
        </div>
    </div>
</div>
<hr>