<div class="form-row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Select files to upload') }}</label>
        <div class="file-loading">
            <input id="customer_document" name="customer_document[]" type="file" multiple id="customer_document">
        </div>
        
        <input type="text" style="display: none;" name="hide_customer_ids" id="hide_customer_ids" value="{{$hide_customer_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_customer_document_block" data-parsley-required-message="Please select and upload at least one Client Document!">
        <div id="error_customer_document_block" class="help-block"></div>
    </div>
</div>

@section('scripts')
@parent
<script type="text/javascript">

    var customer_initialpreview = {!! json_encode($customer_initialpreview) !!};
    var customer_initialpreviewconfig = {!! json_encode($customer_initialpreviewconfig) !!};
    
	if(form_type === 'edit') {

	    $("#customer_document").fileinput({
	        theme: "fas",
	        uploadUrl: '/manage/customers/{{$customer->id}}/document_upload/document',
	        uploadAsync: true,
	        uploadExtraData: function() {
	            return {
	                _token: _token,
	                customer_id: $("#customer_id").val()
	            };
	        },
	        showPreview : true,
	        showCancel : false,
	        fileActionSettings: {
	            showUpload: false,
	        },
	        minFileCount: 1,
	        maxTotalFileCount: 12,
	        // allowedPreviewTypes: ['image', 'html', 'text', 'video', 'audio', 'flash', 'object'],
	        // initialPreviewFileType: 'image',
	        overwriteInitial: false,
	        initialPreviewAsData: true,
	        initialPreview: customer_initialpreview,
	        initialPreviewConfig: customer_initialpreviewconfig,
	        preferIconicPreview: true,
	        previewFileIconSettings: { // configure your icon file extensions
	            'doc': '<i class="fas fa-file-word text-primary"></i>',
	            'xls': '<i class="fas fa-file-excel text-success"></i>',
	            'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
	            'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
	            'zip': '<i class="fas fa-file-archive text-muted"></i>',
	            'htm': '<i class="fas fa-file-code text-info"></i>',
	            'txt': '<i class="fas fa-file-alt text-info"></i>',
	            'mov': '<i class="fas fa-file-video text-warning"></i>',
	            'mp3': '<i class="fas fa-file-audio text-warning"></i>',
	            // note for these file types below no extension determination logic
	            // has been configured (the keys itself will be used as extensions)
	            'jpg': '<i class="fas fa-file-image text-danger"></i>',
	            'gif': '<i class="fas fa-file-image text-muted"></i>',
	            'png': '<i class="fas fa-file-image text-primary"></i>'
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
	            'htm': function(ext) {
	                return ext.match(/(htm|html)$/i);
	            },
	            'txt': function(ext) {
	                return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
	            },
	            'mov': function(ext) {
	                return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
	            },
	            'mp3': function(ext) {
	                return ext.match(/(mp3|wav)$/i);
	            }
	        },
	    }).on('fileuploaded', function(event, data, previewId, index) {
	        if(data.response.ids){
	            $('#error_documents_block').html('');
	            var hide_customer_ids_val = $("#hide_customer_ids").val();
	            hide_customer_ids_val += data.response.ids[0] + ',';
	            $('#hide_customer_ids').val(hide_customer_ids_val);
	        }
	    }).on('filesuccessremove', function(event, id) {

	    }).on("filedeleted", function(event,key,data) {
	        var customer_img_id = (JSON.parse(data.responseText)).customer_image_id;
	        var hide_customer_ids = $("#hide_customer_ids").val();
	        hide_customer_ids = hide_customer_ids.replace((customer_img_id + ','),'');
	        $('#hide_customer_ids').val(hide_customer_ids);
	    });
	}

</script>
@stop
