@extends('appshell::layouts.default')

@section('styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@stop

@section('title')
    {{ __('Editing') }} {{ $report->title }}
@stop

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header">
        {{ __('Report Details') }}
    </div>

    {!! Form::model($report, ['route' => ['report.reports.update', $report], 'method' => 'PUT']) !!}

    <div class="card-block">
            @include('report::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Update Report') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
