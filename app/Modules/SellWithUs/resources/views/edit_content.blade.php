@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }}
@stop

@section('content')
<div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create SellWithUs')
                <a href="{{ route('sell_with_us.sell_with_uss.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Sell With Us Main') }}
                </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <div class="container">

                <!-- Image Section -->
                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Banner Image*') }}</label>
                        <span style="color: red; display: none;" id="spn_banner_require">The Banner Image field is required.</span>

                        <div class="file-loading">
                            <input id="sellwithus_banner_image" name="sellwithus_banner_image" type="file" accept="image/*">
                        </div>

                        @if(!$sell_with_us_data->isEmpty())
                            <input type="text" style="display: none;" name="hide_sellwithus_image_ids" id="hide_sellwithus_image_ids" value="{{  $sell_with_us->banner_image }}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
                        @else
                            <input type="text" style="display: none;" name="hide_sellwithus_image_ids" id="hide_sellwithus_image_ids" value="" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
                        @endif
                            <div id="error_uploaded_item_images_block" class="help-block"></div>
                            <span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x  480px.</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>{{ __('Title Header') }}</label>
                        <div class="form-group">
                            {{ Form::text('blog_header_1', (!$sell_with_us_data->isEmpty()) ? $sell_with_us->blog_header_1 : null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('blog_header_1') ? ' is-invalid' : ''),
                                    'id' => 'blog_header_1',
                                    'placeholder' => __('Title Header')
                                ])
                            }}
                            <span style="color: red; display: none;" id="spn_header_1_require">blog_header_1 field is required.</span>

                            @if ($errors->has('blog_header_1'))
                                <div class="invalid-feedback">{{ $errors->first('blog_header_1') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>{{ __('Title Blog') }}</label>
                        <span style="color: red; display: none;" id="spn_text_require1">The blog_one field is required.</span>
                        @if(!$sell_with_us_data->isEmpty())
                            <textarea id="blog_one" name="blog_one" class="summernote">{!! json_decode($sell_with_us->blog_1) !!}</textarea>
                        @else
                            <textarea id="blog_one" name="blog_one" class="summernote"></textarea>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <label>{{ __('Blog Header One') }}</label>
                        <div class="form-group">
                            {{ Form::text('blog_header_2', (!$sell_with_us_data->isEmpty()) ? $sell_with_us->blog_header_2 : null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('blog_header_2') ? ' is-invalid' : ''),
                                    'id' => 'blog_header_2',
                                    'placeholder' => __('Blog Header One')
                                ])
                            }}
                            <span style="color: red; display: none;" id="spn_header_2_require">blog_header_2 field is required.</span>


                            @if ($errors->has('blog_header_2'))
                                <div class="invalid-feedback">{{ $errors->first('blog_header_2') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label>{{ __('Blog One') }}</label>
                        <span style="color: red; display: none;" id="spn_text_require2">The blog_two field is required.</span>
                        @if(!$sell_with_us_data->isEmpty())
                            <textarea id="blog_two" name="blog_two" class="summernote">{!! json_decode($sell_with_us->blog_2) !!}</textarea>
                        @else
                            <textarea id="blog_two" name="blog_two" class="summernote"></textarea>
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
            <button class="btn btn-success" onclick="edit_content();">{{ __('Update Sell With Us Info') }}</button>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
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

    $(document).ready(function() {
        // $('#summernote').summernote({height: 250});
        $('#blog_one').summernote({
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

        $('#blog_two').summernote({
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


        $("#sellwithus_banner_image").fileinput({
            theme: "fas",
            uploadUrl: '/manage/sell_with_uss/banner_image_upload',
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
            // ;
            if(data.response.ids){
                $('#error_uploaded_item_images_block').html('');
                var hide_item_ids_val = $("#hide_sellwithus_image_ids").val();
                hide_item_ids_val += data.response.ids[0] + ',';
                $('#hide_sellwithus_image_ids').val(data.response.saved_filepath);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on('fileclear', function(event) {
        })
        .on("filedeleted", function(event,key,data) {
            var item_img_id = (JSON.parse(data.responseText)).item_image_id;
            var hide_item_ids = $("#hide_sellwithus_image_ids").val();
            hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
            $('#hide_sellwithus_image_ids').val(hide_item_ids);
        }).on('fileimageloaded', function(event, previewId) {
        });
    });

    function edit_content(){

        var blog_1 = '';
        var blog_2 = '';

        var banner_image = $('#hide_sellwithus_image_ids').val();
        var caption = $('#caption').val();
        var blog_header_1 = $('#blog_header_1').val();
        var blog_header_2 = $('#blog_header_2').val();

        if (!$('#blog_one').summernote('isEmpty')){
            blog_1 = JSON.stringify($('.summernote').eq(0).summernote('code'));
        }

        if (!$('#blog_two').summernote('isEmpty')){
            blog_2 = JSON.stringify($('.summernote').eq(1).summernote('code'));
        }

        $.ajax({
            url: "/manage/sell_with_uss/updateContent",
            type: 'post',
            data:{banner_image, caption, blog_header_1, blog_1, blog_header_2, blog_2,  _token: _token },
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == '1'){
                //    location.reload();
                    window.location.href = "{{ route('sell_with_us.sell_with_uss.infopage')}}";
                }
            }
        });
    }
</script>
@stop
