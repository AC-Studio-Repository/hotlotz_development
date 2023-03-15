@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new Glossary') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Enter Glossary') }}

    </div>

    {!! Form::model($glossary, ['route' => 'glossary.glossarys.store', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            @include('glossary::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create Glossary') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
