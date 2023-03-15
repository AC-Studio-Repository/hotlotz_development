@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new Sell With Us') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Enter Sell With Us') }}

    </div>

    {!! Form::model($sell_with_us, ['route' => 'sell_with_us.sell_with_uss.store', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            @include('sell_with_us::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create Sell With Us') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
