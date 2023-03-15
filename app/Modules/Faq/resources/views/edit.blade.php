@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing FAQ') }}
@stop

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header">
        {{ __('FAQ Details') }}
    </div>

    {!! Form::model($faq, ['route' => ['faq.faqs.update', $faq], 'method' => 'PUT']) !!}

    <div class="card-block">
            @include('faq::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Update FAQ') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
