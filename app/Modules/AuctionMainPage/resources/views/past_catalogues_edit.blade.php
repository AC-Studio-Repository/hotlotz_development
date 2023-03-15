@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }}
@stop

@section('content')
<div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create AuctionMainPage')
                <a href="{{ route('past_catalogues.past_cataloguess.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Past Catalogues Main') }}
                </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <div class="container">

                <!-- Image Section -->
                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Banner Image') }}</label>
                        <span style="color: red; display: none;" id="spn_banner_require">The Banner Image field is required.</span>

                        <div class="file-loading">
                            <input id="past_catalogues_banner_image" name="past_catalogues_banner_image" type="file" accept="image/*">
                        </div>

                        @if(!$past_catalogues_data->isEmpty())
                            <input type="text" style="display: none;" name="hide_past_catalogues_image_ids" id="hide_past_catalogues_image_ids" value="{{  $banner }}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
                        @else
                            <input type="text" style="display: none;" name="hide_past_catalogues_image_ids" id="hide_past_catalogues_image_ids" value="" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
                        @endif
                        <div id="error_uploaded_item_images_block" class="help-block"></div>
                        <span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x  480px.</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>{{ __('Image Caption') }}</label>
                        <div class="form-group">
                            {{ Form::text('caption', (!$past_catalogues_data->isEmpty()) ? $past_catalogues->caption : null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('caption') ? ' is-invalid' : ''),
                                    'id' => 'caption',
                                    'placeholder' => __('Image Caption')
                                ])
                            }}
                            <span style="color: red; display: none;" id="spn_caption_require">caption field is required.</span>
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
                            {{ Form::text('title_header', (!$past_catalogues_data->isEmpty()) ? $past_catalogues->title_header : null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('title_header') ? ' is-invalid' : ''),
                                    'id' => 'title_header',
                                    'placeholder' => __('Title Header')
                                ])
                            }}
                            <span style="color: red; display: none;" id="spn_header_1_require">title_header field is required.</span>

                            @if ($errors->has('title_header'))
                                <div class="invalid-feedback">{{ $errors->first('title_header') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>{{ __('Title Blog') }}</label>
                        <span style="color: red; display: none;" id="spn_text_require1">The title_blog field is required.</span>
                        @if(!$past_catalogues_data->isEmpty())
                            <textarea id="title_blog" name="title_blog" class="summernote">{!! json_decode($past_catalogues->title_blog) !!}</textarea>
                        @else
                            <textarea id="title_blog" name="title_blog" class="summernote"></textarea>
                        @endif
                    </div>
                </div>

                <!-- <div class="form-group row">
                    <div class="col-md-6 text-danger">
                        <i class="zmdi zmdi-alert-circle-o zmdi-hc-fw"> </i> [ * ] This field should not be left blank .
                    </div>
                </div>  -->
            </div>

        <div class="card-footer">
            <button class="btn btn-success" onclick="edit_content();">{{ __('Update Past Catalogues Main') }}</button>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>
@stop

@section('scripts')

@include('content_management::summernote')

<!-- ### Fileinput ### -->

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

    $(document).ready(function() {
        // $('#summernote').summernote({height: 250});
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

        $("#past_catalogues_banner_image").fileinput({
            theme: "fas",
            uploadUrl: '/manage/auction_main_pages/bannerPastCataloguesUpload',
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
                image: { width: "300px", height: "auto" },
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
                var hide_item_ids_val = $("#hide_past_catalogues_image_ids").val();
                hide_item_ids_val += data.response.ids[0] + ',';
                $('#hide_past_catalogues_image_ids').val(data.response.saved_filepath);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on('fileclear', function(event) {
        })
        .on("filedeleted", function(event,key,data) {
            var item_img_id = (JSON.parse(data.responseText)).item_image_id;
            var hide_item_ids = $("#hide_past_catalogues_image_ids").val();
            hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
            $('#hide_past_catalogues_image_ids').val(hide_item_ids);
        }).on('fileimageloaded', function(event, previewId) {
        });
    });

    function edit_content(){

        var title_blog = '';

        var banner_image = $('#hide_past_catalogues_image_ids').val();
        var caption = $('#caption').val();
        var title_header = $('#title_header').val();

        if (!$('#title_blog').summernote('isEmpty')){
            title_blog = JSON.stringify($(".note-editable").html());
        }

        $.ajax({
            url: "/manage/auction_main_pages/updatePastCataloguesContent",
            type: 'post',
            data:{banner_image, caption, title_header, title_blog, _token: _token },
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == '1'){
                    window.location.href = "{{ route('auction_main_page.auction_main_pages.pastCataloguesIndex')}}";
                }
            }
        });
    }
</script>
@stop
