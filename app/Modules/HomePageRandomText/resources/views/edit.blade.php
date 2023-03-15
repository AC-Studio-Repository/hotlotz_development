@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $random_text->title }}
@stop

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header">
        {{ __('Ticker Display Details') }}
    </div>

    {!! Form::model($random_text, ['route' => ['home_page_random_text.home_page_random_texts.update', $random_text], 'method' => 'PUT']) !!}

    <div class="card-block">
            @include('home_page_random_text::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Update Ticker Display') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
