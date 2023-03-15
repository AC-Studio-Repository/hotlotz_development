@extends('appshell::layouts.default')

@section('styles')
@stop

@section('title')
    {{ __('Create new client') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header font-sm">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="contact_details-tab" data-toggle="tab" href="#contact_details" role="tab" aria-controls="contact_details" aria-selected="false">{{ __('Contact Details') }}</a>
                <a class="nav-item nav-link" id="seller_details-tab" data-toggle="tab" href="#seller_details" role="tab" aria-controls="seller_details" aria-selected="false">{{ __('Seller Details') }}</a>
                <a class="nav-item nav-link" id="buyer_details-tab" data-toggle="tab" href="#buyer_details" role="tab" aria-controls="buyer_details" aria-selected="false">{{ __('Buyer Details') }}</a>
                <a class="nav-item nav-link" id="marketing-tab" data-toggle="tab" href="#marketing" role="tab" aria-controls="marketing" aria-selected="false">{{ __('Marketing') }}</a>
                <a class="nav-item nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false">{{ __('Documents') }}</a>
            </div>
        </nav>
    </div>

    {!! Form::model($customer, ['route' => 'customer.customers.store', 'id'=>'frmCreateCustomer', 'data-parsley-validate'=>'true', 'autocomplete' => 'off','files' => 'true', 'enctype'=>'multipart/form-data', 'data-parsley-excluded'=>"input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden" ]) !!}
        <div>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="contact_details" role="tabpanel" aria-labelledby="contact_details-tab">
                    @include('customer::contact_details')
                </div>
                <div class="tab-pane fade" id="seller_details" role="tabpanel" aria-labelledby="seller_details-tab">
                    @include('customer::seller_details')
                </div>
                <div class="tab-pane fade" id="buyer_details" role="tabpanel" aria-labelledby="buyer_details-tab">
                    @include('customer::buyer_details')
                </div>
                <div class="tab-pane fade" id="marketing" role="tabpanel" aria-labelledby="marketing-tab">
                    @include('customer::marketing')
                </div>
                <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                    @include('customer::documents')
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create customer') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop

@section('scripts')

<!-- ### Additional CSS ### -->
<link href="{{asset('plugins/bootstrap-fileinput-5.0.8/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">


<!-- Select2 CSS -->
<link href="{{asset('plugins/select2-develop/dist/css/select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/select2-bootstrap4-theme-master/dist/select2-bootstrap4.min.css')}}" rel="stylesheet" />

<!-- Parsley CSS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">

<link rel="stylesheet" href="{{asset('/custom/css/bootstrap-duallistbox.css')}}">

<!-- Handlebars JS -->
<script src="{{asset('custom/js/handlebars-v4.7.3.min.js')}}"></script>

<style>
    .select2-container--default .select2-selection--single {
        padding: 2px;
        height: 32px;
        font-size: 1.2em;
        position: relative;
    }

    .select2-selection__arrow {
        margin-top: 2px;
    }
</style>

<!-- ### Additional JS ### -->
<!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you wish to resize images before upload. This must be loaded before fileinput.min.js -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/piexif.min.js')}}" type="text/javascript"></script>

<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
This must be loaded before fileinput.min.js -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/sortable.min.js')}}" type="text/javascript"></script>

<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for
HTML files. This must be loaded before fileinput.min.js -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/purify.min.js')}}" type="text/javascript"></script>

<!-- popper.min.js below is needed if you use bootstrap 4.x (for popover and tooltips). You can also use the bootstrap js js/plugins/purify.min.js
3.3.x versions without popper.min.js. -->
<script src="{{asset('custom/js/popper.min.js')}}"></script>

<!-- bootstrap.min.js below is needed if you wish to zoom and preview file content in a detail modal
dialog. bootstrap 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
<script src="{{asset('custom/js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>

<!-- the main fileinput plugin file -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/fileinput.js')}}"></script>

<!-- following theme script is needed to use the Font Awesome 5.x theme (`fas`) -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/themes/fas/theme.min.js')}}"></script>


<!-- Select2 JS -->
<script src="{{asset('plugins/select2-develop/dist/js/select2.full.min.js')}}"></script>
<!-- Parsley JS -->
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<script src="{{asset('/custom/js/jquery.bootstrap-duallistbox.js')}}"></script>

<script>

    new Vue({
        el: '#app',
        data: {
            customerType: '{{ old('type') ?: $customer->type->value() }}',
            formType: '{{ request()->segment(count(request()->segments())) }}',
            gstRegister: '{{ old('seller_gst_registered') ?: $customer->seller_gst_registered }}',
        }
    });

    var _token = $('input[name="_token"]').val();
    var form_type = {!! json_encode($form_type) !!};

    $(function(){

        // $('.divOldPassword').hide();
        // $('.divNewPassword').hide();
        // $('#change_password').click(function () {
        //     $('.divOldPassword').show();
        //     $('.divNewPassword').show();
        // });

        //#Dual Listbox for Marketing
        var category_interests = $('.category_interests').bootstrapDualListbox({
            nonSelectedListLabel: 'Available Categories',
            selectedListLabel: 'Selected Categories',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            showFilterInputs: false,
            // nonSelectedFilter: 'ion ([7-9]|[1][0-2])',
            infoText: false,
        });

        $('[id="bootstrap-duallistbox-selected-list_category_interests[]"]').removeAttr('name');


        //#Customer Documents
        $("#customer_document").fileinput({
            theme: "fas",
            uploadUrl: '/manage/customers/0/document_upload/document',
            uploadAsync: true,
            uploadExtraData: function() {
                return {
                    _token: _token,
                };
            },
            showPreview : true,
            showCancel : false,
            fileActionSettings: {
                showUpload: false,
            },
            minFileCount: 1,
            maxTotalFileCount: 12,
            overwriteInitial: false,
            initialPreviewAsData: true,
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
    });
</script>

@include('customer::customer_js')

@stop
