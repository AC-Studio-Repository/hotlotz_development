@extends('appshell::layouts.default')

@section('title')
    {{ __('System Configurations') }}
@stop

@section('content')
    @can('view sys configs')
    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        @can('edit sys configs')
        {!! Form::model($sys_config, ['route' => 'sys_config.sys_configs.save', 'autocomplete' => 'off']) !!}

            <div class="card-block">
                @include('sys_config::_form')
            </div>

            <div class="card-footer">
                <button class="btn btn-success">{{ __('Save') }}</button>
                <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>

        {!! Form::close() !!}
        @endcan
    </div>
    @endcan

@stop

@section('scripts')

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

<script src="{{asset('custom/js/pickadate/lib/picker.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/picker.date.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/picker.time.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/legacy.js')}}"></script>

@include('sys_config::sysconfig_js')
@stop
