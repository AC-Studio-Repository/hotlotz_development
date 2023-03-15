@extends('appshell::layouts.default')

@section('styles')

<link href="{{asset('plugins/bootstrap-fileinput-5.0.8/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">



<style>


</style>
@stop

@section('title')
    {{ __('Editing') }} {{ $customer->getName() }}
@stop

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">

                <a class="nav-item nav-link active" id="documents-tab" data-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false">{{ __('Documents') }}</a>
            </div>
        </nav>
    </div>

    {!! Form::model($customer, ['route' => ['customer.customers.update', $customer], 'method' => 'PUT', 'id'=>'frmEditCustomer', 'data-parsley-validate'=>'true', 'autocomplete' => 'off','files' => 'true', 'enctype'=>'multipart/form-data', 'data-parsley-excluded'=>"input[type=button], input[type=submit], input[type=reset], input[type=hidden], [disabled], :hidden" ]) !!}

        <div>
            <input type="hidden" name="customer_id" id="customer_id" value="{{$customer->id}}">
            <div class="tab-content" id="nav-tabContent">

                <div class="tab-pane fade show active" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                    @include('customer::documents')
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Update Client') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop

@section('scripts')

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





<script>

    $(document).ready(function() {
        var url1 = 'http://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/FullMoon2010.jpg/631px-FullMoon2010.jpg',
            url2 = 'http://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Earth_Eastern_Hemisphere.jpg/600px-Earth_Eastern_Hemisphere.jpg';
        $("#customer_document").fileinput({
            initialPreview: [url1, url2],
            initialPreviewAsData: true,
            initialPreviewConfig: [
                {caption: "Moon.jpg", downloadUrl: url1, size: 930321, width: "120px", key: 1},
                {caption: "Earth.jpg", downloadUrl: url2, size: 1218822, width: "120px", key: 2}
            ],
            deleteUrl: "/site/file-delete",
            overwriteInitial: false,
            maxFileSize: 100,
            initialCaption: "The Moon and the Earth"
        });
    });

</script>
@stop
