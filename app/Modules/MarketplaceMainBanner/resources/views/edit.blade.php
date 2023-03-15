@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $marketplace_main_banner->name }}
@stop

@section('content')

    {!! Form::model($marketplace_main_banner, ['route'  => ['marketplace_main_banner.marketplace_main_banners.update', $marketplace_main_banner], 'method' => 'PUT',  'enctype'=>'multipart/form-data', 'data-parsley-validate'=>'true'])
    !!}
        <div class="col-12 col-lg-12 col-xl-12">
            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __('Marketplace Home Banner Details') }}
                </div>
                <div class="card-block">
                    @include('marketplace_main_banner::_form')
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Update') }}</button>
                    <a href="{{ route('marketplace_main_banner.marketplace_main_banners.show',['marketplace_main_banner'=>$marketplace_main_banner])}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 col-xl-3">

        </div>
    {!! Form::close() !!}
    
@stop

@section('scripts')
<!-- Parsley JS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>

<script type="text/javascript">

    var _token = $('input[name="_token"]').val();

    $(function(){

        $('#summernote').summernote({
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
    });
</script>
@include('marketplace_main_banner::marketplace_main_banner_js')
@stop
