@extends('appshell::layouts.default')

@section('styles')
@stop

@section('title')
    {{ __('Create new main banner') }}
@stop

@section('content')

    {!! Form::open(['route' => 'main_banner.main_banners.store', 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row', 'data-parsley-validate'=>'true']) !!}

        <div class="col-12 col-lg-12 col-xl-12">
            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __('Main Banner Details') }}
                </div>
                <div class="card-block">
                    @include('main_banner::_form')
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Create') }}</button>
                    <a href="{{ route('main_banner.main_banners.index')}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 col-xl-3">

        </div>

    {!! Form::close() !!}

@stop

@section('scripts')
<!-- Parsley JS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

@include('main_banner::mainbanner_js')

@stop