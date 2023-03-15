@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $policy->menu }}
@stop

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header">
        {{ __('Policy Details') }}
    </div>

    {!! Form::model($policy, ['route' => ['policy.policies.update', $policy], 'method' => 'PUT']) !!}

    <div class="card-block">
            @include('policy::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Update Policy') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop

@section('scripts')
@include('content_management::content_management_js')

<script type="text/javascript">

    var _token = $('input[name="_token"]').val();
    var lastest_id = '{{ $lastest_id }}';
    var initialpreview = {!! json_encode($initialpreview) !!};;
    var initialpreviewconfig = {!! json_encode($initialpreviewconfig) !!};;

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
            initialPreview: initialpreview,
            initialPreviewConfig: initialpreviewconfig,
            // preferIconicPreview: true,
            previewFileIconSettings: { // configure your icon file extensions
                'doc': '<i class="fas fa-file-word text-primary"></i>',
                'xls': '<i class="fas fa-file-excel text-success"></i>',
                'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                'zip': '<i class="fas fa-file-archive text-muted"></i>',
                'txt': '<i class="fas fa-file-alt text-info"></i>',
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
                'txt': function(ext) {
                    return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
                },
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
            console.log('File batch upload success ',event, data);

        }).on("filedeleted", function(event,key,data) {
            console.log('filedeleted', event,key,data);
            $('#hide_file_path').val('');
            $('#hide_full_path').val('');
        });
    });
</script>
@stop
