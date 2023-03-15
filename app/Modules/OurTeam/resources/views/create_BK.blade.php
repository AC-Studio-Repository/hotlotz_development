@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new Team Member') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Create new Team Member') }}

    </div>

    {!! Form::model($our_team, ['route' => 'our_team.our_teams.store', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            @include('our_team::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@endsection

@section('scripts')

@include('content_management::summernote')

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

<style type="text/css">
   .mt-50{margin-top: 50px;}
</style>

<script>

    var _token = $('input[name="_token"]').val();
    var hid_record_id = '{{ $hid_record_id }}';
    var lastest_id = '{{ $lastest_id }}';
    var item_initialpreview = '{{ $banner }}';
    var initialpreview2 = '{{ $banner2 }}';

    $(function(){

        $('#summernote').summernote({
                height: 250,
                focus: true,
                width: 950,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['view', ['fullscreen']]
            ],
            callbacks: {
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            }
        });

         //#Our Team Document
        $("#our_team_image").fileinput({
            theme: "fas",
            uploadUrl: '/manage/our_teams/imageUpload',
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
                var hide_item_ids_val = $("#hide_team_image_ids").val();
                hide_item_ids_val += data.response.ids[0] + ',';
                $('#hide_team_image_ids').val(data.response.saved_filepath);
                $("#hide_team_full_path_ids").val(data.response.saved_storage_filepath);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on("filedeleted", function(event,key,data) {
            var item_img_id = (JSON.parse(data.responseText)).item_image_id;
            var hide_item_ids = $("#hide_item_ids").val();
            var hide_item_path = $("#hide_team_full_path_ids").val();
            hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
            $('#hide_team_image_ids').val(hide_item_ids);
            $("#hide_team_full_path_ids").val(hide_item_path);
        }).on('fileimageloaded', function(event, previewId) {
        });

        $("#team_image2").fileinput({
            theme: "fas",
            uploadUrl: '/manage/our_teams/imageUpload',
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
            initialPreview: initialpreview2,
            // initialPreview: item_initialpreview,
            // initialPreviewConfig: item_initialpreviewconfig,
            // maxFilePreviewSize: 10240,
        }).on('fileuploaded', function(event, data, previewId, index) {
            // ;
            if(data.response.ids){
                $('#error_uploaded_item_images_block').html('');
                var hide_item_ids_val = $("#hide_team_image2_ids").val();
                hide_item_ids_val += data.response.ids[0] + ',';
                $('#hide_team_image2_ids').val(data.response.saved_filepath);
                $("#hide_team_full_path2_ids").val(data.response.saved_storage_filepath);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on("filedeleted", function(event,key,data) {
            var item_img_id = (JSON.parse(data.responseText)).item_image_id;
            var hide_item_ids = $("#hide_team_image2_ids").val();
            var hide_item_path = $("#hide_team_full_path2_ids").val();
            hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
            $('#hide_team_image2_ids').val(hide_item_ids);
            $("#hide_team_full_path2_ids").val(hide_item_path);
        }).on('fileimageloaded', function(event, previewId) {
        });
    });
</script>
@stop
