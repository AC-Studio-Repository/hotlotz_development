@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new Policy') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Enter Policy Name') }}

    </div>

    {!! Form::model($policy, ['route' => 'policy.policies.store', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            @include('policy::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create Policy') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@endsection

@section('scripts')

@include('content_management::content_management_js')

<style type="text/css">
   .mt-50{margin-top: 50px;}
</style>

<script>

    var _token = $('input[name="_token"]').val();
    var lastest_id = '{{ $lastest_id }}';

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

         //#Policy Document
        $("#policy_document").fileinput({
            theme: "fas",
            uploadUrl: '/manage/policies/{{ $policy_id }}/document_upload',
            uploadAsync: true,
            uploadExtraData: function() {
                return {
                    _token: _token,
                    lastest_id: lastest_id
                };
            },
            showPreview : true,
            showCancel : false,
            fileActionSettings: {
                showUpload: false,
            },
            minFileCount: 1,
            maxTotalFileCount: 1,
            overwriteInitial: false,
            initialPreviewAsData: true,
            // preferIconicPreview: true,
            previewFileIconSettings: { // configure your icon file extensions
                'doc': '<i class="fas fa-file-word text-primary"></i>',
                'xls': '<i class="fas fa-file-excel text-success"></i>',
                'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                'zip': '<i class="fas fa-file-archive text-muted"></i>',
                // 'htm': '<i class="fas fa-file-code text-info"></i>',
                'txt': '<i class="fas fa-file-alt text-info"></i>',
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
            console.log('File uploaded success ',event, data);
            if(data.response.saved_full_path){
                console.log('saved_file_path : ',data.response.saved_file_path);
                $('#error_documents_block').html('');
                $('#hide_file_path').attr('value',data.response.saved_file_path);
                $('#hide_full_path').attr('value',data.response.saved_full_path);
            }

        }).on('filebatchuploadsuccess', function(event, data) {
            console.log('File batch upload success');

        }).on("filedeleted", function(event,key,data) {
            console.log('filedeleted', event,key,data);
            $('#hide_file_path').val('');
            $('#hide_full_path').val('');
        });
    });
</script>
@stop