@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $what_we_sell->name }}
@stop

@section('styles')
@stop

@section('content')
<div class="row">

    <div class="col-12 col-lg-12 col-xl-12">
        {!! Form::model($what_we_sell, ['route'  => ['what_we_sell.what_we_sells.update', $what_we_sell], 'method' => 'PUT',  'enctype'=>'multipart/form-data', 'data-parsley-validate'=>'true'])
        !!}
            <div class="col-12 col-lg-12 col-xl-12">
                <div class="card card-accent-secondary">
                    <div class="card-header">
                        {{ __('What We Sell Details') }}
                    </div>
                    <div class="card-block">
                        @include('what_we_sells::_form')
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success">{{ __('Update') }}</button>
                        <a href="{{ route('what_we_sell.what_we_sells.show',['what_we_sell'=>$what_we_sell])}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 col-xl-3">

            </div>
        {!! Form::close() !!}
    </div>

    <div class="col-12 col-lg-4 col-xl-3">
    </div>

</div>
@stop

@section('scripts')
<!-- Parsley JS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>

<script type="text/javascript">
    

    $(function() {
        $('#description').summernote({
            height: 250,
            focus: true,
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

@include('what_we_sells::whatwesell_js')

@stop