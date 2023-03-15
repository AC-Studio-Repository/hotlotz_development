<div class="row">
    <div class="col-md-4">
        <label>{{ __('Title*') }}</label>
        <div class="form-group">
             {{ Form::text('title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title*')
                ])
            }}

            @if ($errors->has('title'))
                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Description*') }}</label>
        <div class="form-group">
            {{ Form::text('description', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('description') ? ' is-invalid' : ''),
                    'placeholder' => __('Description*')
                ])
            }}

            @if ($errors->has('description'))
                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
            @endif
        </div>
    </div>
</div>

<!-- Image Section -->
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Image') }}</label>
        <div class="file-loading">
            <input id="sp_image" name="sp_image[]" type="file" multiple accept="image/*">
        </div>
        
        <input type="text" style="display: none;" name="hide_sp_image_ids" id="hide_sp_image_ids" value="{{$hide_image_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload at least one Item image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
    </div>
</div>
<hr>

<div class="form-group row">
    <div class="col-md-6 text-danger">
        <i class="zmdi zmdi-alert-circle-o zmdi-hc-fw"> </i> [ * ] This field should not be left blank .
    </div>
</div>

@section('scripts')
    <script src='https://code.jquery.com/jquery-1.12.4.js'></script>
    <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script>
@stop