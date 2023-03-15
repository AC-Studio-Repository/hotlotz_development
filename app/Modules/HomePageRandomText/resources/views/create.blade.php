@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new Ticker Display') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Enter Ticker Display') }}

    </div>

    {!! Form::model($random_text, ['route' => 'home_page_random_text.home_page_random_texts.store', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            @include('home_page_random_text::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create Ticker Display') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
