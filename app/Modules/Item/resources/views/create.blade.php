@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new item') }}
@stop

@section('content')
    <div class="col-12 col-lg-12 col-xl-12">
        <div class="card card-accent-secondary">
            <div class="card-header font-sm">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link disabled" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="false">{{ __('Overview') }}</a>
                        <a class="nav-item nav-link active" id="item_details-tab" data-toggle="tab" href="#item_details" role="tab" aria-controls="item_details" aria-selected="true">{{ __('Cataloguing') }}</a>
                        <a class="nav-item nav-link disabled" id="item_lifecycle-tab" data-toggle="tab" href="#item_lifecycle" role="tab" aria-controls="item_lifecycle" aria-selected="false">Valuation & Lifecycle</a>
                        <a class="nav-item nav-link disabled" id="item_seller_package-tab" data-toggle="tab" href="#item_seller_package" role="tab" aria-controls="item_seller_package" aria-selected="false">Fee Structure</a>
                        <a class="nav-item nav-link disabled" id="item_purchase_details-tab" data-toggle="tab" href="#item_purchase_details" role="tab" aria-controls="item_purchase_details" aria-selected="false">Purchase Details</a>
                        <a class="nav-item nav-link disabled" id="item_history-tab" data-toggle="tab" href="#item_history" role="tab" aria-controls="item_history" aria-selected="false">Item History</a>
                    </div>
                </nav>
            </div>
            <div>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    </div>
                    <div class="tab-pane fade show active" id="item_details" role="tabpanel" aria-labelledby="item_details-tab">
                        @include('item::itemdetails.create_item_details')
                    </div>
                    <div class="tab-pane fade" id="item_lifecycle" role="tabpanel" aria-labelledby="item_lifecycle-tab">
                    </div>
                    <div class="tab-pane fade" id="item_seller_package" role="tabpanel" aria-labelledby="item_seller_package-tab">
                    </div>
                    <div class="tab-pane fade" id="item_purchase_details" role="tabpanel" aria-labelledby="item_purchase_details-tab">
                    </div>
                    <div class="tab-pane fade" id="item_history" role="tabpanel" aria-labelledby="item_history-tab">
                    </div>
                </div>
                <input type="hidden" name="page_action" id="page_action" value="create">
            </div>
        </div>
    </div>

    @include('item::customer_modal')
@stop

@section('scripts')

<!-- Select2 CSS -->
<link href="{{asset('plugins/select2-develop/dist/css/select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/select2-bootstrap4-theme-master/dist/select2-bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Select2 JS -->
<script src="{{asset('plugins\select2-develop\dist\js\select2.full.min.js')}}"></script>

<!-- Parsley -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}" />
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<!-- Bootstrap Multiselect with Checkbox -->
<link rel="stylesheet" href="{{asset('plugins\bootstrap-multiselect-dropdown\css\bootstrap-multiselect.css')}}">
<script src="{{asset('plugins\bootstrap-multiselect-dropdown\js\bootstrap-multiselect.js')}}"></script>

<!-- Handlebar JS -->
<script src="{{asset('custom/js/handlebars-v4.7.3.min.js')}}"></script>


<!-- ### Fileinput ### -->
<link href="{{asset('plugins/bootstrap-fileinput-5.0.8/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/piexif.min.js')}}" type="text/javascript"></script>
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/sortable.min.js')}}" type="text/javascript"></script>
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/purify.min.js')}}" type="text/javascript"></script>
<script src="{{asset('custom/js/popper.min.js')}}"></script>
<script src="{{asset('custom/js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>
<!-- the main fileinput plugin file -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/fileinput.min.js')}}"></script>
<!-- following theme script is needed to use the Font Awesome 5.x theme (`fas`) -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/themes/fas/theme.min.js')}}"></script>



<style type="text/css">
    .card-header {
        font-size: 1.1em !important;
    }
</style>


<script type="text/javascript">

    var _token = $('input[name="_token"]').val();
    var page_action = $('#page_action').val();
    var sub_category = '';
    var condition = '';

    $(function() {

        $("#item_image").fileinput({
            theme: "fas",
            uploadUrl: '/manage/items/0/image_upload',
            uploadAsync: false,
            uploadExtraData: function() {
                return {
                    _token: _token,
                    item_id: $("#item_id").val()
                };
            },
            showPreview : true,
            showCancel : false,
            fileActionSettings: {
                showUpload: false,
                // showDrag: false,
            },
            minFileCount: 1,
            // maxFileCount: 12,
            maxTotalFileCount: 12,
            // minImageWidth: 263,
            // minImageHeight: 217,
            // maxImageWidth: 263,
            // maxImageHeight: 217,
            allowedFileExtensions: ["jpg", "jpeg"],
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreviewFileType: 'image',
            // maxFileSize: 10240,
            // maxFilePreviewSize: 10240,
        }).on('filebatchuploadsuccess', function(event, data) {
            console.log('File batch upload success ',event, data);
            var response = data.response;
            if(response.ids){
                console.log('data ids: ',response.ids);
                $('#error_uploaded_item_images_block').html('');
                var hide_item_image_ids_val = $("#hide_item_image_ids").val();
                console.log('hide_item_image_ids_val : ',hide_item_image_ids_val);
                hide_item_image_ids_val += response.ids;
                console.log('hide_item_image_ids_val : ',hide_item_image_ids_val);
                $('#hide_item_image_ids').attr('value',hide_item_image_ids_val);
            }

        }).on("filedeleted", function(event,key,data) {
            var item_img_id = (JSON.parse(data.responseText)).item_image_id;
            var hide_item_image_ids = $("#hide_item_image_ids").val();
            hide_item_image_ids = hide_item_image_ids.replace((item_img_id + ','),'');
            $('#hide_item_image_ids').attr('value',hide_item_image_ids);

        }).on('filesorted', function(event, params) {
            console.log('File sorted ', params.previewId, params.oldIndex, params.newIndex, params.stack);
            var hide_item_image_ids = '';
            $.each(params.stack,function(i, data){
                console.log('item_image_id', data.extra.id);
                hide_item_image_ids += data.extra.id + ',';
            });
            console.log('hide_item_image_ids_val', hide_item_image_ids);
            $('#hide_item_image_ids').attr('value',hide_item_image_ids);
            $('#image_reorder').attr('value','edit');
        });


        $("#item_video").fileinput({
            theme: "fas",
            uploadUrl: '/manage/items/0/video_upload',
            uploadAsync: false,
            uploadExtraData: function() {
                return {
                    _token: _token,
                    item_id: $("#item_id").val()
                };
            },
            showPreview : true,
            showCancel : false,
            fileActionSettings: {
                showUpload: false,
                showDrag: false,
            },
            minFileCount: 1,
            maxTotalFileCount: 3,
            allowedFileTypes:['video'],
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreviewFileType: 'video',
            maxFileSize: 10240,
            maxFilePreviewSize: 10240,
        }).on('filebatchuploadsuccess', function(event, data) {
            // console.log('File batch upload success ',event, data);
            var response = data.response;
            if(response.ids){
                $('#error_uploaded_item_videos_block').html('');
                var hide_item_video_ids_val = $("#hide_item_video_ids").val();
                hide_item_video_ids_val += response.ids;
                $('#hide_item_video_ids').attr('value', hide_item_video_ids_val);
            }

        }).on("filedeleted", function(event,key,data) {
            var item_vid_id = (JSON.parse(data.responseText)).item_video_id;
            var hide_item_video_ids = $("#hide_item_video_ids").val();
            hide_item_video_ids = hide_item_video_ids.replace((item_vid_id + ','),'');
            $('#hide_item_video_ids').attr('value', hide_item_video_ids);

        });


        $("#item_internal_photo").fileinput({
            theme: "fas",
            uploadUrl: '/manage/items/0/internal_photo_upload',
            uploadAsync: false,
            uploadExtraData: function() {
                return {
                    _token: _token,
                    item_id: $("#item_id").val()
                };
            },
            showPreview : true,
            showCancel : false,
            fileActionSettings: {
                showUpload: false,
                showDrag: false,
            },
            minFileCount: 1,
            maxTotalFileCount: 12,
            // minImageWidth: 263,
            // minImageHeight: 217,
            // maxImageWidth: 263,
            // maxImageHeight: 217,
            allowedFileExtensions: ["jpg", "jpeg", "png"],
            overwriteInitial: false,
            initialPreviewAsData: true,
            initialPreviewFileType: 'image',
            // maxFileSize: 10240,
            // maxFilePreviewSize: 10240,
        }).on('filebatchuploadsuccess', function(event, data) {
            console.log('File batch upload success ',event, data);
            var response = data.response;
            if(response.ids){
                console.log('data ids: ',response.ids);
                $('#error_uploaded_internal_photos_block').html('');
                var hide_item_internal_photo_ids_val = $("#hide_item_internal_photo_ids").val();
                console.log('hide_item_internal_photo_ids_val : ',hide_item_internal_photo_ids_val);
                hide_item_internal_photo_ids_val += response.ids;
                console.log('hide_item_internal_photo_ids_val : ',hide_item_internal_photo_ids_val);
                $('#hide_item_internal_photo_ids').attr('value', hide_item_internal_photo_ids_val);
            }

        }).on("filedeleted", function(event,key,data) {
            var item_internal_photo_id = (JSON.parse(data.responseText)).item_internal_photo_id;
            var hide_item_internal_photo_ids = $("#hide_item_internal_photo_ids").val();
            hide_item_internal_photo_ids = hide_item_internal_photo_ids.replace((item_internal_photo_id + ','),'');
            $('#hide_item_internal_photo_ids').attr('value', hide_item_internal_photo_ids);

        });


    });


</script>

@include('item::commonjs')
@include('item::itemdetails.cataloguingjs')

@stop