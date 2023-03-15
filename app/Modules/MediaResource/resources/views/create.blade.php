@extends('appshell::layouts.default')

@section('title')
    {{ __('Create Media Resource') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Enter Media Resource') }}

    </div>

    {!! Form::model($media_resource, ['route' => 'media_resource.media_resources.store', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            @include('media_resource::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create Media Resource') }}</button>
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

<style type="text/css">
   .mt-50{margin-top: 50px;}
</style>

<script>

    var _token = $('input[name="_token"]').val();
    var hid_record_id = '{{ $hid_record_id }}';
    var lastest_id = '{{ $lastest_id }}';

    $(function(){

         //#Careers Document
        $("#media_resource_document").fileinput({
            theme: "fas",
            uploadUrl: '/manage/media_resources/documentUpload',
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
            minFileCount: 1,
            maxFileCount: 1,
            maxTotalFileCount: 1,
            overwriteInitial: true,
            initialPreviewAsData: true,
            preferIconicPreview: true,
            previewFileIconSettings: { // configure your icon file extensions
                'doc': '<i class="fas fa-file-word text-primary"></i>',
                'xls': '<i class="fas fa-file-excel text-success"></i>',
                'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                'zip': '<i class="fas fa-file-archive text-muted"></i>',
                // 'htm': '<i class="fas fa-file-code text-info"></i>',
                // 'txt': '<i class="fas fa-file-alt text-info"></i>',
                // 'mov': '<i class="fas fa-file-video text-warning"></i>',
                // 'mp3': '<i class="fas fa-file-audio text-warning"></i>',
                // note for these file types below no extension determination logic
                // has been configured (the keys itself will be used as extensions)
                // 'jpg': '<i class="fas fa-file-image text-danger"></i>',
                // 'gif': '<i class="fas fa-file-image text-muted"></i>',
                // 'png': '<i class="fas fa-file-image text-primary"></i>'
            },
            previewFileExtSettings: { // configure the logic for determining icon file extensions
                'doc': function(ext) {
                    return ext.match(/(doc|docx)$/i);
                },
                'xls': function(ext) {
                    return ext.match(/(xls|xlsx)$/i);
                },
                'ppt': function(ext) {
                    return ext.match(/(ppt|pptx)$/i);
                },
                'zip': function(ext) {
                    return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
                },
                // 'htm': function(ext) {
                //     return ext.match(/(htm|html)$/i);
                // },
                'txt': function(ext) {
                    return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
                },
                // 'mov': function(ext) {
                //     return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
                // },
                // 'mp3': function(ext) {
                //     return ext.match(/(mp3|wav)$/i);
                // }
            },
        }).on('fileuploaded', function(event, data, previewId, index) {
            if(data.response.ids){
                $('#error_documents_block').html('');
                var hide_media_resource_doc_ids_val = $("#hide_media_resource_doc_ids").val();
                hide_media_resource_doc_ids_val += data.response.ids[0] + ',';
                $('#hide_media_resource_doc_ids').val(data.response.saved_filepath);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on("filedeleted", function(event,key,data) {
            var customer_img_id = (JSON.parse(data.responseText)).customer_image_id;
            var hide_media_resource_doc_ids = $("#hide_media_resource_doc_ids").val();
            hide_media_resource_doc_ids = hide_media_resource_doc_ids.replace((customer_img_id + ','),'');
            $('#hide_media_resource_doc_ids').val(hide_media_resource_doc_ids);
        });
    });
</script>
@stop
