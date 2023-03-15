{!! Form::model($item, ['route'  => ['item.items.update', $item], 'method' => 'PUT', 'id'=>'frmEditItem', 'data-parsley-validate'=>'true', 'autocomplete' => 'off','files' => 'true', 'enctype'=>'multipart/form-data' ]) !!}

<div class="card-block">
	<input type="hidden" name="tab_name" value="cataloguing">
    @include('item::itemdetails._form')
</div>

<div class="card-footer">
    <button class="btn btn-outline-success" id="btnUpdateItem">{{ __('Save') }}</button>
    <a href="{{ route('item.items.show',['item'=>$item])}}" class="btn btn-outline-danger">{{ __('Cancel') }}</a>
</div>

{!! Form::close() !!}

@include('item::customer_modal')

@section('scripts')
@parent

@include('item::itemdetails.cataloguingjs')
<script type="text/javascript">
    var item_initialpreview = {!! json_encode($item_initialpreview) !!};
    var item_initialpreviewconfig = {!! json_encode($item_initialpreviewconfig) !!};
    var item_video_initialpreview = {!! json_encode($item_video_initialpreview) !!};
    var item_video_initialpreviewconfig = {!! json_encode($item_video_initialpreviewconfig) !!};
    var internal_photo_initialpreview = {!! json_encode($internal_photo_initialpreview) !!};
    var internal_photo_initialpreviewconfig = {!! json_encode($internal_photo_initialpreviewconfig) !!};


    $("#item_image").fileinput({
        theme: "fas",
        uploadUrl: '/manage/items/{{$item->id}}/image_upload',
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
        },
        minFileCount: 1,
        maxTotalFileCount: 12,
        allowedFileExtensions: ["jpg", "jpeg"],
        overwriteInitial: false,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: item_initialpreview,
        initialPreviewConfig: item_initialpreviewconfig,
        // maxFileSize: 10240,
        // maxFilePreviewSize: 10240,
    }).on('fileuploaded', function(event, data, previewId, index) {

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
        console.log('filedeleted', event,key,data);
        var item_img_id = (JSON.parse(data.responseText)).item_image_id;
        console.log('item_img_id', item_img_id);
        var hide_item_image_ids = $("#hide_item_image_ids").val();
        hide_item_image_ids = hide_item_image_ids.replace((item_img_id + ','),'');
        console.log('hide_item_image_ids', hide_item_image_ids);
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
        uploadUrl: '/manage/items/{{$item->id}}/video_upload',
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
        initialPreview: item_video_initialpreview,
        initialPreviewConfig: item_video_initialpreviewconfig,
        maxFileSize: 10240,
        maxFilePreviewSize: 10240,
    }).on('fileuploaded', function(event, data, previewId, index) {
    }).on('filebatchuploadsuccess', function(event, data) {
        console.log('File batch upload success ',event, data);
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
        uploadUrl: '/manage/items/{{$item->id}}/internal_photo_upload',
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
        maxTotalFileCount: 5,
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        overwriteInitial: false,
        initialPreviewAsData: true,
        initialPreviewFileType: 'image',
        initialPreview: internal_photo_initialpreview,
        initialPreviewConfig: internal_photo_initialpreviewconfig,
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

</script>
@stop