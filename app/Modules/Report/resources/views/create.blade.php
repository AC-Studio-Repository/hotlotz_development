@extends('appshell::layouts.default')

@section('styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@stop

@section('title')
    {{ __('Create new report') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Enter Report Details') }}

    </div>
    {!! Form::model($report, ['route' => 'report.reports.store', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            @include('report::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create report') }}</button>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
