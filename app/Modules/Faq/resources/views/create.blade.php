@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new FAQ') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Enter FAQ') }}

    </div>

    {!! Form::model($faq, ['route' => 'faq.faqs.store', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            @include('faq::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create FAQ') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
