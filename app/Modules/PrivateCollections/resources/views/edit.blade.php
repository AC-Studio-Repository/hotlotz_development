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

        {!! Form::model($blogs, ['route' => ['private_collections.private_collectionss.updateContent'], 'method' => 'POST']) !!}
        <div class="card-block">
            <div class="container">
            <input type="hidden" id="hid_backend_count" name="hid_backend_count" value="{{ $latest_id }}" />
                <!-- Image Section -->
                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Banner Image') }}</label>

                        <div class="file-loading">
                            <input id="private_collections_banner_image" name="private_collections_banner_image" type="file" accept="image/*">
                        </div>

                        @if(!$private_collections_data->isEmpty())
                            <input type="text" style="display: none;" name="hide_private_collections_image_ids" id="hide_private_collections_image_ids" value="{{  $private_collections->banner_image }}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
                        @else
                            <input type="text" style="display: none;" name="hide_private_collections_image_ids" id="hide_private_collections_image_ids" value="" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">

                        @endif
                            <div id="error_uploaded_item_images_block" class="help-block"></div>
                            <span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x  480px.</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>{{ __('Image Caption') }}</label>
                        <div class="form-group">
                            {{ Form::text('caption', (!$private_collections_data->isEmpty()) ? $private_collections->caption : null, [
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
                            {{ Form::text('blog_header_1', (!$private_collections_data->isEmpty()) ? $private_collections->blog_header_1 : null, [
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
                        @if(!$private_collections_data->isEmpty())
                            <textarea id="title_blog" name="title_blog" class="summernote">{!! $private_collections->blog_1 !!}</textarea>
                        @else
                            <textarea id="title_blog" name="title_blog" class="summernote"></textarea>
                        @endif
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

            <div class="row">
                <div class="col-md-6">
                    <label>{{ __('Key Contact One') }}</label>
                    <div class="form-group">
                        <select class="form-control{{ $errors->has('key_contact_1') ? ' is-invalid' : '' }}" name="key_contact_1">
                        <option value="">Select Key Contact</option>
                        @foreach($ourteam as $team)
                            <option value="{{ $team->id }}" {{ $team->id == $key_contact_1 ? 'selected' : '' }}>{{ $team->name }} ( {!! $team->position !!} )</option>
                        @endforeach
                        </select>

                        @if ($errors->has('key_contact_1'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('key_contact_1') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label>{{ __('Key Contact Two') }}</label>
                    <div class="form-group">
                        <select class="form-control{{ $errors->has('key_contact_2') ? ' is-invalid' : '' }}" name="key_contact_2">
                        <option value="">Select Key Contact</option>
                        @foreach($ourteam as $team)
                            <option value="{{ $team->id }}" {{ $team->id == $key_contact_2 ? 'selected' : '' }}>{{ $team->name }} ( {!! $team->position !!} )</option>
                        @endforeach
                        </select>

                        @if ($errors->has('key_contact_2'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('key_contact_2') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">{{ __('Update Private Collections') }}</button>
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

        $("#private_collections_banner_image").fileinput({
            theme: "fas",
            uploadUrl: '/manage/private_collectionss/banner_image_upload',
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
            // ;
            if(data.response.ids){
                $('#error_uploaded_item_images_block').html('');
                var hide_item_ids_val = $("#hide_private_collections_image_ids").val();
                hide_item_ids_val += data.response.ids[0] + ',';
                $('#hide_private_collections_image_ids').val(data.response.saved_filepath);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on('fileclear', function(event) {
        })
        .on("filedeleted", function(event,key,data) {
            var item_img_id = (JSON.parse(data.responseText)).item_image_id;
            var hide_item_ids = $("#hide_private_collections_image_ids").val();
            hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
            $('#hide_private_collections_image_ids').val(hide_item_ids);
        }).on('fileimageloaded', function(event, previewId) {
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
