@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $email_template->title }}
@stop

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header">
        {{ __('Email Template Details') }}
    </div>

    {!! Form::model($email_template, ['route' => ['email_template.email_templates.update', $email_template], 'method' => 'PUT']) !!}

        <div class="card-block">
            @include('email_template::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Update') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop

@section('scripts')


<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>

<script>
    var _token = $('input[name="_token"]').val();

    $(document).ready(function() {
        $('#summernote').summernote({
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
            ]
        });

    });
</script>
@stop