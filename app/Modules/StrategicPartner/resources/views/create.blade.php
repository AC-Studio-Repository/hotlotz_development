@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new Strategic Partner') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Enter Strategic Partner') }}

    </div>

    {!! Form::model($strategic_partner, ['route' => 'strategic_partner.strategic_partners.store', 'autocomplete' => 'off']) !!}

        <div class="card-block">
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
                    <label class="form-control-label">{{ __('Image*') }}</label>
                    <div class="file-loading">
                        <input id="sp_image" name="sp_image" type="file" accept="image/*">
                    </div>

                    {{ Form::hidden('hide_sp_image_ids', $hide_image_ids, [
                            'class' => 'form-control form-control-md' . ($errors->has('hide_sp_image_ids') ? ' is-invalid' : ''),
                            'data-parsley-required' => 'false',
                            'id' => 'hide_sp_image_ids',
                            'data-parsley-errors-container' => '#error_uploaded_item_images_block',
                            'data-parsley-required-message' => 'Please select and upload image!'
                        ])
                    }}

                    @if ($errors->has('hide_sp_image_ids'))
                        <div class="invalid-feedback">{{ $errors->first('hide_sp_image_ids') }}</div>
                    @endif

                    {{ Form::hidden('hide_sp_full_filepath', $hide_image_ids, [
                            'class' => 'form-control form-control-md' . ($errors->has('hide_sp_full_filepath') ? ' is-invalid' : ''),
                            'data-parsley-required' => 'false',
                            'id' => 'hide_sp_full_filepath',
                            'data-parsley-errors-container' => '#error_uploaded_item_images_block',
                            'data-parsley-required-message' => 'Please select and upload image!'
                        ])
                    }}

                    @if ($errors->has('hide_sp_full_filepath'))
                        <div class="invalid-feedback">{{ $errors->first('hide_sp_full_filepath') }}</div>
                    @endif
                    <div id="error_uploaded_item_images_block" class="help-block"></div>
                    <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
                </div>
            </div>
            <hr>

            <div class="form-group row">
                <div class="col-md-6 text-danger">
                    <i class="zmdi zmdi-alert-circle-o zmdi-hc-fw"> </i> [ * ] This field should not be left blank .
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create Strategic Partner') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop

@section('scripts')

<link href="{{asset('plugins/bootstrap-fileinput-5.0.8/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous"> -->
<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

<!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you wish to resize images before upload. This must be loaded before fileinput.min.js -->
<script src="{{asset('plugins\bootstrap-fileinput-5.0.8\js\plugins\piexif.min.js')}}" type="text/javascript"></script>

<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
This must be loaded before fileinput.min.js -->
<script src="{{asset('plugins\bootstrap-fileinput-5.0.8\js\plugins\sortable.min.js')}}" type="text/javascript"></script>

<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for
HTML files. This must be loaded before fileinput.min.js -->
<script src="{{asset('plugins\bootstrap-fileinput-5.0.8\js\plugins\purify.min.js')}}" type="text/javascript"></script>

<!-- popper.min.js below is needed if you use bootstrap 4.x (for popover and tooltips). You can also use the bootstrap js js/plugins/purify.min.js
3.3.x versions without popper.min.js. -->
<script src="{{asset('custom\js\popper.min.js')}}"></script>

<!-- bootstrap.min.js below is needed if you wish to zoom and preview file content in a detail modal
dialog. bootstrap 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
<script src="{{asset('custom\js\bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>

<!-- the main fileinput plugin file -->
<script src="{{asset('plugins\bootstrap-fileinput-5.0.8\js\fileinput.js')}}"></script>

<!-- following theme script is needed to use the Font Awesome 5.x theme (`fas`) -->
<script src="{{asset('plugins\bootstrap-fileinput-5.0.8\themes\fas\theme.min.js')}}"></script>


<script type="text/javascript">

    var _token = $('input[name="_token"]').val();
    var item_initialpreview = '{{ $banner }}';
    var hid_record_id = '{{ $hid_record_id }}';
    var lastest_id = '{{ $lastest_id }}';

    $(function() {

        $("#sp_image").fileinput({
            theme: "fas",
            uploadUrl: '/manage/strategic_partners/image_upload',
            uploadAsync: true,
            uploadExtraData: function() {
                return {
                    _token: _token,
                    hid_record_id: hid_record_id,
                    lastest_id: lastest_id
                };
            },
            showPreview : true,
            showCancel : false,
            fileActionSettings: {
                showUpload: false,
            },
            previewSettings: {
                image: { width: "300px", height: "auto" },
            },
            minFileCount: 1,
            maxFileCount: 1,
            maxTotalFileCount: 1,
            // minImageWidth: 263,
            // minImageHeight: 217,
            // maxImageWidth: 263,
            // maxImageHeight: 217,
            allowedFileExtensions: ["jpg", "jpeg", "png"],
            overwriteInitial: true,
            initialPreviewAsData: true,
            initialPreviewFileType: 'image',
            initialPreview: item_initialpreview,
            // initialPreview: item_initialpreview,
            // initialPreviewConfig: item_initialpreviewconfig,
            // maxFilePreviewSize: 10240,
        }).on('fileuploaded', function(event, data, previewId, index) {
            // ;
            if(data.response.ids){
                $('#error_uploaded_item_images_block').html('');
                var hide_item_ids_val = $("#hide_sp_image_ids").val();
                hide_item_ids_val += data.response.ids[0] + ',';
                $('#hide_sp_image_ids').val(data.response.saved_filepath);
                $("#hide_sp_full_filepath").val(data.response.saved_storage_filepath);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on("filedeleted", function(event,key,data) {
            var item_img_id = (JSON.parse(data.responseText)).item_image_id;
            var hide_item_ids = $("#hide_item_ids").val();
            var hide_item_path = $("#hide_sp_full_filepath").val();
            hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
            $('#hide_sp_image_ids').val(hide_item_ids);
            $("#hide_sp_full_filepath").val(hide_item_path);
        }).on('fileimageloaded', function(event, previewId) {
        });
    });

</script>

@stop
