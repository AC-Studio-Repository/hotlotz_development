@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing Glossary') }}
@stop

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header">
        {{ __('Glossary Details') }}
    </div>

    {!! Form::model($glossary, ['route' => ['glossary.glossarys.update', $glossary], 'method' => 'PUT']) !!}

    <div class="card-block">
            @include('glossary::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Update Glossary') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
