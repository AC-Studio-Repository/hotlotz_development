@extends('appshell::layouts.default')

@section('title')
    {{ __('What We Sell Main') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        {!! Form::model($whatwesell_info, ['route' => 'whatwesell.whatwesells.storeInfo', 'autocomplete' => 'off']) !!}
        <div class="card-block">
            <div class="row">
                    <div class="col-md-4">
                        <label>{{ __('Caption') }}</label>
                        <div class="form-group">
                            <input type="text" id="whatwesell_info_value" name="whatwesell_info_value" class="form-control" data-parsley-required="true" data-parsley-required-message="This value is required." value="{{ $whatwesell_info[0] }}" />
                        </div>
                    </div>
                </div>

                <!-- Image Section -->
                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Banner Image') }}</label>

                        <div class="file-loading">
                            <input id="whatwesell_info_image" name="whatwesell_info_image" type="file" accept="image/*">
                        </div>

                        <input type="text" style="display: none;" name="hide_whatwesell_info_image_ids" id="hide_whatwesell_info_image_ids" value="{{$hide_image_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload at least one whatwesell Banner image!">
                        <div id="error_uploaded_item_images_block" class="help-block"></div>
                        <span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x  480px.</span>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Save Info') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
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

    $(function() {

        $("#whatwesell_info_image").fileinput({
            theme: "fas",
            uploadUrl: '/manage/whatwesells/info_banner_upload',
            uploadAsync: true,
            uploadExtraData: function() {
                return {
                    _token: _token
                };
            },
            dropZoneEnabled: true,
            showPreview : true,
            showCancel : false,
            fileActionSettings: {
                showUpload: false,
            },
            previewSettings: {
                image: { width: "800px", height: "auto" },
            },
            minFileCount: 1,
            maxFileCount: 1,
            maxTotalFileCount: 1,
            allowedFileExtensions: ["jpg", "jpeg", "png"],
            overwriteInitial: true,
            initialPreviewAsData: true,
            initialPreviewFileType: 'image',
            initialPreview: item_initialpreview,
        }).on('fileuploaded', function(event, data, previewId, index) {
            // ;
            if(data.response.ids){
                $('#error_uploaded_item_images_block').html('');
                var hide_item_ids_val = $("#hide_whatwesell_info_image_ids").val();
                hide_item_ids_val += data.response.ids[0] + ',';
                $('#hide_whatwesell_info_image_ids').val(data.response.saved_filepath);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on('fileclear', function(event) {
        })
        .on("filedeleted", function(event,key,data) {
            var item_img_id = (JSON.parse(data.responseText)).item_image_id;
            var hide_item_ids = $("#hide_whatwesell_info_image_ids").val();
            hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
            $('#hide_whatwesell_info_image_ids').val(hide_item_ids);
        }).on('fileimageloaded', function(event, previewId) {
        });
    });

</script>

@stop
