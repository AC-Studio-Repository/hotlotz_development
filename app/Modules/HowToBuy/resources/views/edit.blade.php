@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }}
@stop

@section('content')
<div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                <a href="#" onclick="add_new_blog();" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Add New Blog') }}
                </a>
            </div>

        </div>

        {!! Form::model($blogs, ['route' => ['how_to_buy.how_to_buys.updateContent'], 'method' => 'POST']) !!}
        <div class="card-block">
            <div class="container">
            <input type="hidden" id="hid_backend_count" name="hid_backend_count" value="{{ $latest_id }}" />
                <!-- Image Section -->
                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Banner Image') }}</label>

                        <div class="file-loading">
                            <input id="howtobuy_banner_image" name="howtobuy_banner_image" type="file" accept="image/*">
                        </div>

                        @if(!$how_to_buy_data->isEmpty())
                            <input type="text" style="display: none;" name="hide_howtobuy_image_ids" id="hide_howtobuy_image_ids" value="{{  $how_to_buy->banner_image }}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
                        @else
                            <input type="text" style="display: none;" name="hide_howtobuy_image_ids" id="hide_howtobuy_image_ids" value="" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
                        @endif
                            <div id="error_uploaded_item_images_block" class="help-block"></div>
                            <span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x  480px.</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>{{ __('Image Caption') }}</label>
                        <div class="form-group">
                            {{ Form::text('caption', (!$how_to_buy_data->isEmpty()) ? $how_to_buy->caption : null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('caption') ? ' is-invalid' : ''),
                                    'id' => 'caption',
                                    'placeholder' => __('Image Caption')
                                ])
                            }}

                            @if ($errors->has('caption'))
                                <div class="invalid-feedback">{{ $errors->first('caption') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>{{ __('Title Header') }}</label>
                        <div class="form-group">
                            {{ Form::text('blog_header_1', (!$how_to_buy_data->isEmpty()) ? $how_to_buy->blog_header_1 : null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('blog_header_1') ? ' is-invalid' : ''),
                                    'id' => 'blog_header_1',
                                    'placeholder' => __('Title Header')
                                ])
                            }}

                            @if ($errors->has('blog_header_1'))
                                <div class="invalid-feedback">{{ $errors->first('blog_header_1') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>{{ __('Title Blog') }}</label>
                        @if(!$how_to_buy_data->isEmpty())
                            <textarea id="title_blog" name="title_blog" class="summernote">{!! $how_to_buy->blog_1 !!}</textarea>
                        @else
                            <textarea id="title_blog" name="title_blog" class="summernote"></textarea>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Select files to upload') }}</label>
                        <div class="file-loading">
                            <input id="how_to_buy_file_upload" name="how_to_buy_file_upload" type="file" accept="application/pdf, .xls, .xlsx, text/plain, application/zip, .doc, .ppt, .pptx">
                        </div>

                        @if(!$how_to_buy_data->isEmpty())
                        <input type="text" style="display: none;" name="hide_how_to_buy_uploaded_file_ids" id="hide_how_to_buy_uploaded_file_ids" value="{{  $hide_how_to_buy_uploaded_file_ids }}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload at least one file!">

                        <input type="text" style="display: none;" name="hide_how_to_buy_uploaded_filename" id="hide_how_to_buy_uploaded_filename" value="{{  $hide_how_to_buy_uploaded_filename }}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
                    @else
                        <input type="text" style="display: none;" name="hide_how_to_buy_uploaded_file_ids" id="hide_how_to_buy_uploaded_file_ids" value="" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload at least one file!">

                        <input type="text" style="display: none;" name="hide_how_to_buy_uploaded_filename" id="hide_how_to_buy_uploaded_filename" value="" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
                    @endif
                        <div id="error_customer_document_block" class="help-block"></div>
                    </div>
                </div>

                @if($status == 'edit')
                @foreach($blogs as $key=>$blog)
                @php
                    $index = $key + 1;
                @endphp
                <input type="hidden" id="hid_edit_id_{{ $blog->id }}" name="hid_edit_id_{{ $blog->id }}" value="{{ $blog->id }}" />
                            <input type="hidden" id="hid_delete_id_{{ $blog->id }}" name="hid_delete_id_{{ $blog->id }}" value="0" />
                <div class="row" id="toggle_title_{{ $blog->id }}">
                    <div class="col-md-4">
                        <label>{{ __('Blog Header '.$index) }}</label>
                        <div class="form-group">
                            {{ Form::text('title_'.$blog->id,  $blog->title, [
                                    'class' => 'form-control form-control-md' . ($errors->has('title_'.$blog->id) ? ' is-invalid' : ''),
                                    'id' => 'title_'.$blog->id,
                                    'placeholder' => __('Blog Header '.$index)
                                ])
                            }}

                            @if ($errors->has('title_'.$blog->id))
                                <div class="invalid-feedback">{{ $errors->first('title_'.$blog->id) }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row" id="toggle_blog_{{ $blog->id }}">
                    <div class="col-md-12">
                        <label>{{ __('Blog '.$index) }}</label>
                        <textarea id="blog_{{ $blog->id }}" name="blog_{{ $blog->id }}" class="summernote">{!! $blog->blog !!}</textarea>
                    </div>
                </div>

                <a href="#" id="toggle_button_{{ $blog->id }}" onclick="remove_blog('{{ $blog->id }}');" class="btn btn-sm btn-outline-success">
                    <i class="zmdi zmdi-minus"></i>
                    {{ __('Remove Blog') }}
                </a>

                <hr>
                @endforeach
            @endif
            </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">{{ __('Update How To Buy') }}</button>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
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
 var item_initialpreview = '{{ $banner }}';
 var status = '{{ $status }}';
 var latest_id = {{ $latest_id }};
 var ids = <?php echo json_encode($id_array); ?>;
 var blog_count = {{ $blog_count }};
 var upload_file_initialpreview = '{{ $hide_how_to_buy_uploaded_file_ids }}';

    $(document).ready(function() {
        $('#title_blog').summernote({
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

        $("#howtobuy_banner_image").fileinput({
            theme: "fas",
            uploadUrl: '/manage/how_to_buys/banner_image_upload',
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
            // previewSettings: {
            //     image: { width: "300px", height: "auto" },
            // },
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
                var hide_item_ids_val = $("#hide_howtobuy_image_ids").val();
                hide_item_ids_val += data.response.ids[0] + ',';
                $('#hide_howtobuy_image_ids').val(data.response.saved_filepath);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on('fileclear', function(event) {
        })
        .on("filedeleted", function(event,key,data) {
            var item_img_id = (JSON.parse(data.responseText)).item_image_id;
            var hide_item_ids = $("#hide_howtobuy_image_ids").val();
            hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
            $('#hide_howtobuy_image_ids').val(hide_item_ids);
        }).on('fileimageloaded', function(event, previewId) {
        });


        $("#how_to_buy_file_upload").fileinput({
            theme: "fas",
            uploadUrl: '/manage/how_to_buys/file_upload',
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
            maxFileCount: 1,
            maxTotalFileCount: 1,
            maxFilesize: 0.1,
            overwriteInitial: true,
            initialPreviewAsData: true,
            preferIconicPreview: true,
            initialPreview: upload_file_initialpreview,
            previewFileIconSettings: { // configure your icon file extensions
                'doc': '<i class="fas fa-file-word text-primary"></i>',
                'xls': '<i class="fas fa-file-excel text-success"></i>',
                'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                'zip': '<i class="fas fa-file-archive text-muted"></i>',
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
            },
        }).on('fileuploaded', function(event, data, previewId, index) {
            if(data.response.ids){
                $('#error_documents_block').html('');
                var hide_how_to_buy_uploaded_file_ids_val = $("#hide_how_to_buy_uploaded_file_ids").val();
                var hide_how_to_buy_uploaded_filename_val = $("#hide_how_to_buy_uploaded_filename").val();
                hide_how_to_buy_uploaded_file_ids_val += data.response.ids[0] + ',';
                $('#hide_how_to_buy_uploaded_file_ids').val(data.response.saved_filepath);
                hide_how_to_buy_uploaded_filename_val += data.response.ids[0] + ',';
                $('#hide_how_to_buy_uploaded_filename').val(data.response.saved_property);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on("filedeleted", function(event,key,data) {
            var customer_img_id = (JSON.parse(data.responseText)).customer_image_id;
            var hide_how_to_buy_uploaded_file_ids = $("#hide_how_to_buy_uploaded_file_ids").val();
            hide_media_resource_doc_ids = hide_how_to_buy_uploaded_file_ids.replace((customer_img_id + ','),'');
            $('#hide_how_to_buy_uploaded_file_ids').val(hide_how_to_buy_uploaded_file_ids);
            var hide_how_to_buy_uploaded_filename = $("#hide_how_to_buy_uploaded_filename").val();
            hide_how_to_buy_uploaded_filename = hide_how_to_buy_uploaded_filename.replace((customer_img_id + ','),'');
            $('#hide_how_to_buy_uploaded_filename').val(hide_how_to_buy_uploaded_filename);
        });

        if(status == 'edit')
        {
            for(var i=1; i <= ids.length; i++)
            {
                initialize_summernote_blog(ids[i-1])
            }
        }
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
        latest_id++;
        blog_count++;

        var append_html = '';

        append_html = '<div class="row" id="toggle_title_'+latest_id+'">'+
                '<div class="col-md-4">'+
                    "<label>Blog Header "+blog_count+"</label>"+
                    '<div class="form-group">'+
                        '<input type="text" id="title_'+latest_id+'" name="title_'+latest_id+'" value="" class="form-control form-control-md" placeholder="Blog Header "'+latest_id+' />'+
                    '</div>'+
                '</div>'+
            '</div>';

        append_html += '<div class="row" id="toggle_blog_'+latest_id+'">'+
                '<div class="col-md-12">'+
                    "<label>Blog "+blog_count+"</label>"+
                        '<textarea id="blog_'+latest_id+'" name="blog_'+latest_id+'" value="" class="form-control form-control-md" placeholder="Blog "'+latest_id+'></textarea>'+
                '</div>'+
            '</div><hr />';

        $(append_html).appendTo( ".container" );
        initialize_summernote_blog(latest_id);
        $('#hid_backend_count').val(latest_id);
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