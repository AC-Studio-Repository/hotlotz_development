@extends('appshell::layouts.default')

@section('title')
    {{ __('Sustainable Banner') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
            <div class="card-actionbar">
                <a href="#" onclick="add_new_banner();" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Add New Banner') }}
                </a>
            </div>
        </div>

        {!! Form::model($main_banners, ['route' => ['marketplace_home.marketplace_homes.store_substainable_Info'], 'method' => 'POST']) !!}
        <div class="card-block">
            <input type="hidden" id="hid_item_count" name="hid_item_count" value="{{ $banner_count }}" />
            <input type="hidden" id="hid_backend_count" name="hid_backend_count" value="{{ $latest_id }}" />
            @if($status == 'create')
                <!-- Banner One Section -->
                <div class="row" id="toggle_div_{{ $banner_count }}">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Banner') }}</label>

                        <div class="file-loading">
                            <input id="banner_{{ $banner_count }}" name="banner_{{ $banner_count }}" type="file" accept="image/*">
                        </div>

                        {{ Form::hidden('hide_banner_'.$banner_count, '', [
                                'class' => 'form-control form-control-md' . ($errors->has('hide_banner_'.$banner_count) ? ' is-invalid' : ''),
                                'data-parsley-required' => 'false',
                                'id' => 'hide_banner_'.$banner_count,
                                'data-parsley-errors-container' => '#error_uploaded_item_images_block',
                                'data-parsley-required-message' => 'Please select and upload image!'
                            ])
                        }}

                        @if ($errors->has('hide_banner_'.$banner_count))
                            <div class="invalid-feedback">{{ $errors->first('hide_banner_'.$banner_count) }}</div>
                        @endif

                        {{ Form::hidden('hide_filepath_banner_'.$banner_count, '', [
                                'class' => 'form-control form-control-md' . ($errors->has('hide_filepath_banner_'.$banner_count) ? ' is-invalid' : ''),
                                'data-parsley-required' => 'false',
                                'id' => 'hide_filepath_banner_'.$banner_count,
                                'data-parsley-errors-container' => '#error_uploaded_item_images_block',
                                'data-parsley-required-message' => 'Please select and upload image!'
                            ])
                        }}

                        @if ($errors->has('hide_filepath_banner_'.$banner_count))
                            <div class="invalid-feedback">{{ $errors->first('hide_filepath_banner_'.$banner_count) }}</div>
                        @endif
                        <div id="error_uploaded_item_images_block" class="help-block"></div>
                        <input type="hidden" id="hid_edit_id_{{ $banner_count }}" name="hid_edit_id_{{ $banner_count }}" value="0" />
                        <input type="hidden" id="hid_delete_id_{{ $banner_count }}" name="hid_delete_id_{{ $banner_count }}" value="0" />
                        <span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x 556px.</span>
                    </div>
                </div>

                <div class="row" id="toggle_div_{{ $banner_count }}">
                    <div class="col-md-4">
                        <label>{{ __('Link') }}</label>
                        <div class="form-group">
                            {{ Form::text('link_'.$banner_count, null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('link_'.$banner_count) ? ' is-invalid' : ''),
                                    'placeholder' => __('Link')
                                ])
                            }}

                            @if ($errors->has('link_'.$banner_count))
                                <div class="invalid-feedback">{{ $errors->first('link_'.$banner_count) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <hr>
            @else
                @foreach($main_banners as $banner)

                    <!-- Banner One Section -->
                    <div class="row" id="toggle_img_{{ $banner->id }}">
                        <div class="form-group col-12 col-md-12 col-xl-12">
                            <label class="form-control-label">{{ __('Banner') }}</label>

                            <div class="file-loading">
                                <input id="banner_{{ $banner->id }}" name="banner_{{ $banner->id }}" type="file" accept="image/*">
                            </div>

                            {{ Form::hidden('hide_banner_'.$banner->id, $banner->banner, [
                                    'class' => 'form-control form-control-md' . ($errors->has('hide_banner_'.$banner->id) ? ' is-invalid' : ''),
                                    'data-parsley-required' => 'false',
                                    'id' => 'hide_banner_'.$banner->id,
                                    'data-parsley-errors-container' => '#error_uploaded_item_images_block',
                                    'data-parsley-required-message' => 'Please select and upload image!'
                                ])
                            }}

                            @if ($errors->has('hide_banner_'.$banner->id))
                                <div class="invalid-feedback">{{ $errors->first('hide_banner_'.$banner->id) }}</div>
                            @endif

                            {{ Form::hidden('hide_filepath_banner_'.$banner->id, $banner->file_path, [
                                    'class' => 'form-control form-control-md' . ($errors->has('hide_filepath_banner_'.$banner->id) ? ' is-invalid' : ''),
                                    'data-parsley-required' => 'false',
                                    'id' => 'hide_filepath_banner_'.$banner->id,
                                    'data-parsley-errors-container' => '#error_uploaded_item_images_block',
                                    'data-parsley-required-message' => 'Please select and upload image!'
                                ])
                            }}

                            @if ($errors->has('hide_filepath_banner_'.$banner->id))
                                <div class="invalid-feedback">{{ $errors->first('hide_filepath_banner_'.$banner->id) }}</div>
                            @endif
                            <div id="error_uploaded_item_images_block" class="help-block"></div>
                            <input type="hidden" id="hid_edit_id_{{ $banner->id }}" name="hid_edit_id_{{ $banner->id }}" value="{{ $banner->id }}" />
                            <input type="hidden" id="hid_delete_id_{{ $banner->id }}" name="hid_delete_id_{{ $banner->id }}" value="0" />
                            <span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x 556px.</span>
                        </div>
                    </div>

                    <div class="row" id="toggle_link_{{ $banner->id }}">
                        <div class="col-md-4">
                            <label>{{ __('Link') }}</label>
                            <div class="form-group">
                                {{ Form::text('link_'.$banner->id, $banner->link, [
                                        'class' => 'form-control form-control-md' . ($errors->has('link_'.$banner->id) ? ' is-invalid' : ''),
                                        'placeholder' => __('Link')
                                    ])
                                }}

                                @if ($errors->has('link_'.$banner->id))
                                    <div class="invalid-feedback">{{ $errors->first('link_'.$banner->id) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($banner->id != $latest_id)
                        <a href="#" id="toggle_button_{{ $banner->id }}" onclick="remove_banner('{{ $banner->id }}');" class="btn btn-sm btn-outline-success">
                            <i class="zmdi zmdi-minus"></i>
                            {{ __('Remove Banner') }}
                        </a>
                    @elseif($banner->id == $latest_id && $banner_count == 1)
                        <a href="#" id="toggle_button_{{ $banner->id }}" onclick="remove_banner('{{ $banner->id }}');" class="btn btn-sm btn-outline-success">
                                <i class="zmdi zmdi-minus"></i>
                                {{ __('Remove Banner') }}
                        </a>
                    @endif
                    <hr>
                @endforeach
            @endif
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">{{ __('Save Info') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
@stop

@section('scripts')

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
    // var banner_count = {{ $banner_count }};
    var banner_count = $('#hid_item_count').val();
    var status = '{{ $status }}';
    var previewImages = <?php echo json_encode($previewImages); ?>;
    var latest_id = {{ $latest_id }};

    $(function() {
        if(status == 'create' && latest_id == 0)
        {
            latest_id++;
            $('#hid_backend_count').val(latest_id);
            $('#hid_item_count').val(banner_count);

            var showimage = ''
            initialize_file_upload(banner_count, latest_id, showimage);

        }
        else if(status == 'edit')
        {
            for(var i=1; i <= previewImages.length; i++)
            {
                // ;

                initialize_file_upload(i, previewImages[i-1].id, previewImages[i-1].previewImage);
            }
        }
    });

    function initialize_file_upload(banner_count, latest_id, previewImage)
    {

        if(previewImage == null)
        {
            previewImage = '';
        }

        $("#banner_"+latest_id).fileinput({
            theme: "fas",
            uploadUrl: '/manage/marketplace_homes/sustainable_sorcing_banner_upload',
            uploadAsync: true,
            uploadExtraData: function() {
                return {
                    _token: _token,
                    banner_name: 'banner_'+latest_id,
                    banner_count: latest_id
                };
            },
            dropZoneEnabled: true,
            showPreview : true,
            showCancel : false,
            fileActionSettings: {
                showUpload: false,
            },
            previewSettings: {
                image: { width: "800px", height: "auto" },
            },
            minFileCount: 1,
            maxFileCount: 1,
            maxTotalFileCount: 1,
            allowedFileExtensions: ["jpg", "jpeg", "png"],
            overwriteInitial: true,
            initialPreviewAsData: true,
            initialPreviewFileType: 'image',
            initialPreview: previewImage,
        }).on('fileuploaded', function(event, data, previewId, index) {
            // ;
            if(data.response.ids){
                $('#error_uploaded_item_images_block').html('');
                var hide_item_ids_val = $("#hide_banner_"+latest_id).val();
                hide_item_ids_val += data.response.ids[0] + ',';
                $("#hide_banner_"+latest_id).val(data.response.saved_filepath);
                $("#hide_filepath_banner_"+latest_id).val(data.response.saved_storage_filepath);
            }
        }).on('filesuccessremove', function(event, id) {

        }).on('fileclear', function(event) {
        })
        .on("filedeleted", function(event,key,data) {
            var item_img_id = (JSON.parse(data.responseText)).item_image_id;
            var hide_item_ids = $("#hide_banner_"+latest_id).val();
            var hide_item_path = $("#hide_filepath_banner_"+latest_id).val();
            hide_item_ids = hide_item_ids.replace((item_img_id + ','),'');
            $("#hide_banner_"+latest_id).val(hide_item_ids);
            $("#hide_filepath_banner_"+latest_id).val(hide_item_path);
        }).on('fileimageloaded', function(event, previewId) {
        });
    }

    function add_new_banner()
    {
        banner_count++;
        latest_id++;

        var append_html = '';
        var add_new_previewImage = '';

        append_html += '<div class="row" id="toggle_img_'+latest_id+'">'+
                '<div class="form-group col-12 col-md-12 col-xl-12">'+
                    '<label class="form-control-label">Banner</label>'+
                    '<div class="file-loading">'+
                        '<input id="banner_'+latest_id+'" name="banner_'+latest_id+'" type="file" accept="image/*">'+
                    '</div>'+
                    '<input type="text" name="hide_banner_'+latest_id+'" id="hide_banner_'+latest_id+'" value=""  style="display: none;" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload image!">'+
                    '<input type="text" name="hide_filepath_banner_'+latest_id+'" id="hide_filepath_banner_'+latest_id+'" value=""  style="display: none;" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload image!">'+
                    '<div id="error_uploaded_item_images_block" class="help-block"></div>'+
                    '<input type="hidden" name="hid_edit_id_'+latest_id+'" id="hid_edit_id_'+latest_id+'" value="0" />'+
                    '<input type="hidden" name="hid_delete_id_'+latest_id+'" id="hid_delete_id_'+latest_id+'" value="0" />'+
                    '<span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x 556px.</span>'+
                '</div>'+
            '</div>';

        append_html += '<div class="row" id="toggle_caption_'+latest_id+'">'+
                '<div class="col-md-4">'+
                    "<label>{{ __('Link') }}</label>"+
                    '<div class="form-group">'+
                        '<input type="text" id="link_'+latest_id+'" name="link_'+latest_id+'" value="" class="form-control form-control-md" placeholder="Link" />'+
                    '</div>'+
                '</div>'+
            '</div><hr>';

        $(append_html).appendTo( ".card-block" );
        initialize_file_upload(banner_count, latest_id, add_new_previewImage);
        $('#hid_item_count').val(latest_id);
        $('#hid_backend_count').val(latest_id);
    }

    function remove_banner(id)
    {
        $("#toggle_caption_"+id).hide();
        $("#toggle_img_"+id).hide();
        $("#toggle_link_"+id).hide();
        $("#toggle_button_"+id).hide();
        $("#hid_delete_id_"+id).val(id);
        // $("#id").css("display", "none");
    }

</script>

@stop
