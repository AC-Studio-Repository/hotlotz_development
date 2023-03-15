@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing What We Sell') }}
@stop

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header">
        {{ __('What We Sell Details') }}

        <div class="card-actionbar">
            <a href="#" onclick="add_new_blog();" data-qa="AddNewBlog" class="btn btn-sm btn-outline-success float-right">
                <i class="zmdi zmdi-plus"></i>
                {{ __('Add New Blog') }}
            </a>
        </div>
    </div>

    {!! Form::model($whatwesell, ['route' => ['whatwesell.whatwesells.update', $whatwesell], 'method' => 'PUT']) !!}

    <input type="hidden" id="hid_backend_count" name="hid_backend_count" value="{{ $lastest_id }}" />
    <div class="card-block">
            @include('what_we_sell::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Update What We Sell') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop

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


<script type="text/javascript">

var _token = $('input[name="_token"]').val();
var hid_record_id = '{{ $hid_record_id }}';
var lastest_id = '{{ $lastest_id }}';
var item_initialpreview = '{{ $hide_whatwesell_ids }}';
var item_initialpreview_banner = '{{ $hide_banner_whatwesell_ids }}';

var item_initialpreview_detail_1 = '{{ $hide_whatwesell_detail_1_ids }}';
var item_initialpreview_detail_2 = '{{ $hide_whatwesell_detail_2_ids }}';
var item_initialpreview_detail_3 = '{{ $hide_whatwesell_detail_3_ids }}';
var item_initialpreview_detail_4 = '{{ $hide_whatwesell_detail_4_ids }}';
var item_initialpreview_detail_5 = '{{ $hide_whatwesell_detail_5_ids }}';
var item_initialpreview_detail_6 = '{{ $hide_whatwesell_detail_6_ids }}';
var item_initialpreview_detail_7 = '{{ $hide_whatwesell_detail_7_ids }}';
var item_initialpreview_detail_8 = '{{ $hide_whatwesell_detail_8_ids }}';
var item_initialpreview_detail_9 = '{{ $hide_whatwesell_detail_9_ids }}';
var item_initialpreview_detail_10 = '{{ $hide_whatwesell_detail_10_ids }}';
var item_initialpreview_detail_11 = '{{ $hide_whatwesell_detail_11_ids }}';
var item_initialpreview_detail_12 = '{{ $hide_whatwesell_detail_12_ids }}';

var status = '{{ $status }}';
var blog_count = {{ $blog_count }};
var blog_latest_id = {{ $blog_latest_id }};
var ids = <?php echo json_encode($id_array); ?>;
var key_contact_initial_preview = '{{ $hide_key_contact_image_ids }}';

$(function() {

    $('#description').summernote({
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

    $('#key_contact_position').summernote({
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

    for(var i=1; i <= ids.length; i++)
    {
        initialize_summernote_blog(ids[i-1])
    }

    $("#whatwesell_image").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
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
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });

    $("#whatwesell_banner_image").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_banner,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_banner_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_banner_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_banner_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_banner_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });

    //key contact image
    $("#key_contact_image").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/key_contact_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: key_contact_initial_preview,
    }).on('fileuploaded', function(event, data, previewId, index) {
        // ;
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_key_contact_image_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_key_contact_image_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_key_contact_image_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_key_contact_image_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });

   /*** Detail Images ***/

   $("#whatwesell_detail_image_1").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_1,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_1_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_1_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_1_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_1_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });


    $("#whatwesell_detail_image_2").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_2,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_2_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_2_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_2_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_2_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });


    $("#whatwesell_detail_image_3").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_3,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_3_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_3_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_3_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_3_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });


    $("#whatwesell_detail_image_4").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_4,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_4_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_4_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_4_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_4_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });


    $("#whatwesell_detail_image_5").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_5,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_5_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_5_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_5_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_5_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });


    $("#whatwesell_detail_image_6").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: false,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_6,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_6_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_6_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_6_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_6_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });


    $("#whatwesell_detail_image_7").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_7,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_7_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_7_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_7_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_7_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });


    $("#whatwesell_detail_image_8").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_8,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_8_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_8_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_8_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_8_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });


    $("#whatwesell_detail_image_9").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_9,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_9_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_9_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_9_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_9_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });


    $("#whatwesell_detail_image_10").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_10,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_10_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_10_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_10_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_10_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });


    $("#whatwesell_detail_image_11").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_11,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_11_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_11_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_11_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_11_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });


    $("#whatwesell_detail_image_12").fileinput({
        theme: "fas",
        uploadUrl: '/manage/whatwesells/detail_image_upload',
        uploadAsync: true,
        uploadExtraData: function() {
            return {
                _token: _token,
                hid_record_id: hid_record_id,
                lastest_id: lastest_id
            };
        },
        dropZoneEnabled: true,
        showPreview : true,
        showCancel : false,
        fileActionSettings: {
            showUpload: false,
        },
        previewSettings: {
            image: { width: "500px", height: "auto" },
        },
        minFileCount: 1,
        maxFileCount: 1,
        maxTotalFileCount: 1,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: true,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview_detail_12,
    }).on('fileuploaded', function(event, data, previewId, index) {
        if(data.response.ids){
            $('#error_uploaded_item_images_block').html('');
            var hide_item_ids_val = $("#hide_whatwesell_detail_12_ids").val();
            hide_item_ids_val += data.response.ids[0] + ',';
            $('#hide_whatwesell_detail_12_ids').val(data.response.saved_filepath);
        }
    }).on('filesuccessremove', function(event, id) {

    }).on('fileclear', function(event) {
    })
    .on("filedeleted", function(event,key,data) {
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        var hide_item_ids = $("#hide_whatwesell_detail_12_ids").val();
        hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
        $('#hide_whatwesell_detail_12_ids').val(hide_item_ids);
    }).on('fileimageloaded', function(event, previewId) {
    });
});

function initialize_summernote_blog(id)
    {
        $("#blog_"+id).summernote({
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
    }

    function add_new_blog()
    {
        blog_latest_id++;
        blog_count++;

        var append_html = '';

        append_html = '<hr><div class="row" id="toggle_title_'+blog_latest_id+'">'+
                '<div class="col-md-4">'+
                    "<label>Blog Header "+blog_count+"</label>"+
                    '<div class="form-group">'+
                        '<input type="text" id="title_'+blog_latest_id+'" name="title_'+blog_latest_id+'" value="" class="form-control form-control-md" placeholder="Blog Header "'+blog_latest_id+' />'+
                    '</div>'+
                '</div>'+
            '</div>';

        append_html += '<div class="row" id="toggle_blog_'+blog_latest_id+'">'+
                '<div class="col-md-12">'+
                    "<label>Blog "+blog_count+"</label>"+
                        '<textarea id="blog_'+blog_latest_id+'" name="blog_'+blog_latest_id+'" value="" class="form-control form-control-md" placeholder="Blog "'+blog_latest_id+'></textarea>'+
                '</div>'+
            '</div>';

        $(append_html).appendTo( ".dynamic-blog" );
        initialize_summernote_blog(blog_latest_id);
        $('#hid_backend_count').val(blog_latest_id);
    }

    function remove_blog(id)
    {
        $("#toggle_title_"+id).hide();
        $("#toggle_blog_"+id).hide();
        $("#toggle_button_"+id).hide();
        $("#hid_delete_id_"+id).val(id);
    }

</script>

@stop
