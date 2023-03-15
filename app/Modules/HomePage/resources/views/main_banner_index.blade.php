@extends('appshell::layouts.default')

@section('title')
    {{ __('Main Banner') }}
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

        {!! Form::model($main_banners, ['route' => ['home_page.home_pages.storeInfo'], 'method' => 'POST']) !!}
        <div class="card-block">
            <input type="hidden" id="hid_item_count" name="hid_item_count" value="{{ $banner_count }}" />
            <input type="hidden" id="hid_backend_count" name="hid_backend_count" value="{{ $latest_id }}" />
            @if($status == 'create')

                <div class="row" id="toggle_div_{{ $banner_count }}">
                    <div class="col-md-4">
                        <label class="form-control-label">{{ __('Main Title') }}</label>
                        <div class="form-group">
                            {{ Form::text('main_title_'.$banner_count, null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('main_title_'.$banner_count) ? ' is-invalid' : ''),
                                    'placeholder' => __('Main Title')
                                ])
                            }}

                            @if ($errors->has('main_title_'.$banner_count))
                                <div class="invalid-feedback">{{ $errors->first('main_title_'.$banner_count) }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row" id="toggle_div_{{ $banner_count }}">
                    <div class="col-md-4">
                        <label class="form-control-label">{{ __('Sub Title') }}</label>
                        <div class="form-group">
                            {{ Form::text('caption_'.$banner_count, null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('caption_'.$banner_count) ? ' is-invalid' : ''),
                                    'placeholder' => __('Sub Title')
                                ])
                            }}

                            @if ($errors->has('caption_'.$banner_count))
                                <div class="invalid-feedback">{{ $errors->first('caption_'.$banner_count) }}</div>
                            @endif
                        </div>
                    </div>
                </div>

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
                        <span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x 1000px.</span>
                    </div>
                </div>

                <div class="row" id="toggle_div_{{ $banner_count }}">
                    <div class="col-md-4">
                        <label class="form-control-label">{{ __('Position') }}</label>
                        <div class="form-group">
                            <div class="input-group">
                                <label class="radio-inline" for="position">
                                    {{ Form::radio('position_'.$banner_count, 'left', true, ['id' => 'position_'.$banner_count]) }}
                                    Left
                                    &nbsp;
                                </label>
                                <label class="radio-inline" for="position">
                                    {{ Form::radio('position_'.$banner_count, 'right', false, ['id' => 'position_'.$banner_count]) }}
                                    Right
                                    &nbsp;
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="toggle_div_{{ $banner_count }}">
                    <div class="col-md-4">
                        <label class="form-control-label">{{ __('Color') }}</label>
                        <div class="form-group">
                            <div class="input-group">
                                <label class="radio-inline" for="color" style="margin-right: 5px !important;">
                                    {{ Form::radio('color_'.$banner_count, 'navy', true, ['id' => 'color_'.$banner_count]) }}
                                    <div style="background-color: #0E143E; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                                    &nbsp;
                                </label>
                                <label class="radio-inline" for="color" style="margin-right: 5px !important;">
                                    {{ Form::radio('color_'.$banner_count, 'pink', false, ['id' => 'color_'.$banner_count]) }}
                                    <div style="background-color: #E70C3B; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                                    &nbsp;
                                </label>
                                <label class="radio-inline" for="color" style="margin-right: 5px !important;">
                                    {{ Form::radio('color_'.$banner_count, 'turquoise', false, ['id' => 'color_'.$banner_count]) }}
                                    <div style="background-color: #61C4DD; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                                    &nbsp;
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" id="toggle_div_{{ $banner_count }}">
                    <div class="col-md-4">
                        <label class="form-control-label">{{ __('Link Name') }}</label>
                        <div class="form-group">
                            {{ Form::text('link_name_'.$banner_count, null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('link_name_'.$banner_count) ? ' is-invalid' : ''),
                                    'placeholder' => __('Link Name')
                                ])
                            }}

                            @if ($errors->has('link_name_'.$banner_count))
                                <div class="invalid-feedback">{{ $errors->first('link_name_'.$banner_count) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row" id="toggle_div_{{ $banner_count }}">
                    <div class="col-md-4">
                        <label class="form-control-label">{{ __('link') }}</label>
                        <div class="form-group">
                            {{ Form::text('llink_'.$banner_count, null, [
                                    'class' => 'form-control form-control-md' . ($errors->has('link_'.$banner_count) ? ' is-invalid' : ''),
                                    'placeholder' => __('link')
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
                    <div class="row" id="toggle_main_title_{{ $banner->id }}">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ __('Main Title') }}</label>
                            <div class="form-group">
                                {{ Form::text('main_title_'.$banner->id, $banner->main_title, [
                                        'class' => 'form-control form-control-md' . ($errors->has('main_title_'.$banner->id) ? ' is-invalid' : ''),
                                        'placeholder' => __('Main Title')
                                    ])
                                }}

                                @if ($errors->has('main_title_'.$banner->id))
                                    <div class="invalid-feedback">{{ $errors->first('main_title_'.$banner->id) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row" id="toggle_caption_{{ $banner->id }}">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ __('Sub Title') }}</label>
                            <div class="form-group">
                                {{ Form::text('caption_'.$banner->id, $banner->caption, [
                                        'class' => 'form-control form-control-md' . ($errors->has('caption_'.$banner->id) ? ' is-invalid' : ''),
                                        'placeholder' => __('Sub Title')
                                    ])
                                }}

                                @if ($errors->has('caption_'.$banner->id))
                                    <div class="invalid-feedback">{{ $errors->first('caption_'.$banner->id) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
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
                            <span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x 1000px.</span>
                        </div>
                    </div>

                    <div class="row" id="toggle_position_{{ $banner->id }}">
                        <div class="col-md-4">
                            <label class="form-control-label"s>{{ __('Position') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="radio-inline" for="position">
                                        {{ Form::radio('position_'.$banner->id, 'left', ($banner->position == 'left')?true:false, ['id' => 'position_'.$banner->id]) }}
                                        Left
                                        &nbsp;
                                    </label>
                                    <label class="radio-inline" for="position">
                                        {{ Form::radio('position_'.$banner->id, 'right', ($banner->position == 'right')?true:false, ['id' => 'position_'.$banner->id]) }}
                                        Right
                                        &nbsp;
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="toggle_color_{{ $banner->id }}">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ __('Color') }}</label>
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="radio-inline" for="color" style="margin-right: 5px !important;">
                                        {{ Form::radio('color_'.$banner->id, 'navy', ($banner->color == 'navy')?true:false, ['id' => 'color_'.$banner->id]) }}
                                        <div style="background-color: #0E143E; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                                        &nbsp;
                                    </label>
                                    <label class="radio-inline" for="color" style="margin-right: 5px !important;">
                                        {{ Form::radio('color_'.$banner->id, 'pink', ($banner->color == 'pink')?true:false, ['id' => 'color_'.$banner->id]) }}
                                        <div style="background-color: #E70C3B; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                                        &nbsp;
                                    </label>
                                    <label class="radio-inline" for="color" style="margin-right: 5px !important;">
                                        {{ Form::radio('color_'.$banner->id, 'turquoise', ($banner->color == 'turquoise')?true:false, ['id' => 'color_'.$banner->id]) }}
                                        <div style="background-color: #61C4DD; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                                        &nbsp;
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="toggle_link_name_{{ $banner->id }}">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ __('Link Name') }}</label>
                            <div class="form-group">
                                {{ Form::text('link_name_'.$banner->id, $banner->link_name, [
                                        'class' => 'form-control form-control-md' . ($errors->has('link_name_'.$banner->id) ? ' is-invalid' : ''),
                                        'placeholder' => __('Link Name')
                                    ])
                                }}

                                @if ($errors->has('link_name_'.$banner->id))
                                    <div class="invalid-feedback">{{ $errors->first('link_name_'.$banner->id) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row" id="toggle_learnmore_{{ $banner->id }}">
                        <div class="col-md-4">
                            <label class="form-control-label">{{ __('Link') }}</label>
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
            uploadUrl: '/manage/home_pages/homepage_main_banner_upload',
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

        append_html = '<div class="row" id="toggle_main_title_'+latest_id+'">'+
                '<div class="col-md-4">'+
                    "<label class='form-control-label'>{{ __('Main Title') }}</label>"+
                    '<div class="form-group">'+
                        '<input type="text" id="main_title_'+latest_id+'" name="main_title_'+latest_id+'" value="" class="form-control form-control-md" placeholder="Main Title" />'+
                    '</div>'+
                '</div>'+
            '</div>';

        append_html += '<div class="row" id="toggle_caption_'+latest_id+'">'+
                '<div class="col-md-4">'+
                    "<label class='form-control-label'>{{ __('Sub Title') }}</label>"+
                    '<div class="form-group">'+
                        '<input type="text" id="caption_'+latest_id+'" name="caption_'+latest_id+'" value="" class="form-control form-control-md" placeholder="Sub Title" />'+
                    '</div>'+
                '</div>'+
            '</div>';

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
                    '<span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x 1000px.</span>'+
                '</div>'+
            '</div>';

        append_html += '<div class="row" id="toggle_position_'+latest_id+'">'+
                '<div class="col-md-4"><label class="form-control-label">Position</label><div class="form-group"><div class="input-group"><label class="radio-inline" for="position">'+
                    '<input type="radio" id="position_'+latest_id+'" name="position_'+latest_id+'" value="left" checked > Left &nbsp; </label>'+
                    '<input type="radio" id="position_'+latest_id+'" name="position_'+latest_id+'" value="right"> Right &nbsp; </label>'+
                            '</div></div></div></div>';

        append_html += '<div class="row" id="toggle_color_'+latest_id+'">'+
                '<div class="col-md-4"><label class="form-control-label">Color</label><div class="form-group"><div class="input-group">'+
                    '<label class="radio-inline" for="color" style="margin-right: 5px !important;"><input type="radio" id="color_'+latest_id+'" name="color_'+latest_id+'" value="navy" checked >'+
                    '<div style="background-color: #0E143E; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>&nbsp;</label>'+
                    '<label class="radio-inline" for="color" style="margin-right: 5px !important;"><input type="radio" id="color_'+latest_id+'" name="color_'+latest_id+'" value="pink">'+
                    '<div style="background-color: #E70C3B; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>&nbsp;</label>'+
                    '<label class="radio-inline" for="color" style="margin-right: 5px !important;"><input type="radio" id="color_'+latest_id+'" name="color_'+latest_id+'" value="turquoise">'+
                    '<div style="background-color: #61C4DD; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>&nbsp;</label>'+
                            '</div></div></div></div>';

        append_html += '<div class="row" id="toggle_link_name_'+latest_id+'">'+
                '<div class="col-md-4">'+
                    "<label class='form-control-label'>{{ __('Link Name') }}</label>"+
                    '<div class="form-group">'+
                        '<input type="text" id="link_name_'+latest_id+'" name="link_name_'+latest_id+'" value="" class="form-control form-control-md" placeholder="Link Name" />'+
                    '</div>'+
                '</div>'+
            '</div>';

        append_html += '<div class="row" id="toggle_learnmore_'+latest_id+'">'+
                '<div class="col-md-4">'+
                    "<label class='form-control-label'>{{ __('Link') }}</label>"+
                    '<div class="form-group">'+
                        '<input type="text" id="link_'+latest_id+'" name="link_'+latest_id+'" value="" class="form-control form-control-md" placeholder="Learn More" />'+
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
        $("#toggle_position_"+id).hide();
        $("#toggle_color_"+id).hide();
        $("#toggle_button_"+id).hide();
        $("#toggle_learnmore_"+id).hide();
        $("#toggle_link_name_"+id).hide();
        $("#toggle_main_title_"+id).hide();
        $("#hid_delete_id_"+id).val(id);
        // $("#id").css("display", "none");
    }

</script>

@stop