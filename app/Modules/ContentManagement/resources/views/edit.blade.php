@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        @yield('title')

        <!-- <div class="card-actionbar">
            <a href="{{ route('content_management.termsandconditions.create') }}" class="btn btn-sm btn-outline-success float-right">
                <i class="zmdi zmdi-plus"></i>
                {{ __('Create Terms and Conditions') }}
            </a>
        </div> -->

    </div>

    {!! Form::model($termsandcondition, ['route' => ['content_management.termsandconditions.updateContent'], 'method' => 'POST']) !!}
        <div class="card-block">
            <div class="row mt-15">
                <div class="col-md-12">
                    <textarea name="content" id="summernote">{{ $termsandcondition->value }}</textarea>
                    <!-- <button class="btn btn-info" onclick="show_content();">Show Content</button> -->
                </div>
            </div>
            <br>

            <div class="row">
                <div class="form-group col-12 col-md-12 col-xl-12">
                    <label class="form-control-label">{{ __('Select files to upload') }}</label>
                    <div class="file-loading">
                        <input id="term_doc" name="term_doc" type="file">
                    </div>
                    
                    <input type="text" style="display: none;" name="hide_file_path" id="hide_file_path" value="">
                    <input type="text" style="display: none;" name="hide_full_path" id="hide_full_path" value="" data-parsley-errors-container="#error_term_doc_block" data-parsley-required-message="Please select and upload at least one Document!">
                    <div id="error_term_doc_block" class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <!-- <button class="btn btn-success" onclick="edit_content();">{{ __('Save') }}</button> -->

            <button class="btn btn-success" >{{ __('Save') }}</button>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    {!! Form::close() !!}
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title text-center">Display Content</h2>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </div>

    </div>
</div>
@stop

@section('scripts')

@include('content_management::content_management_js')

<style type="text/css">
   .mt-15 {margin-top: 15px;}
</style>

<script>
    var _token = $('input[name="_token"]').val();
    var initialpreview = {!! json_encode($initialpreview) !!};
    var initialpreviewconfig = {!! json_encode($initialpreviewconfig) !!};

    $(document).ready(function() {
        $('#summernote').summernote({
            height: 250,
            focus: true,
            width: 950,
            toolbar: [
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

        $("#term_doc").fileinput({
            theme: "fas",
            uploadUrl: '/manage/termsandconditions/{{$terms_id}}/document_upload',
            uploadAsync: true,
            uploadExtraData: function() {
                return {
                    _token: _token
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
                $('#error_term_doc_block').html('');
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

    function show_content(){
        $("#myModal .modal-body").html($(".note-editable").html());
        $("#myModal").modal("show");
    }

    function edit_content(){
        var dataToSend = JSON.stringify($(".note-editable").html());

        $.ajax({
            url: "/manage/termsandconditions/ajaxRequest",
            type: 'post',
            data:{content_str:dataToSend, _token: _token },
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == '1'){
                    window.location.href = "{{ route('content_management.termsandconditions.displayContentTandC')}}";
                }
            }
        });
    }
</script>
@endsection
