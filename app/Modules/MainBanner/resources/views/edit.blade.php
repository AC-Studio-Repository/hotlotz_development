@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $main_banner->name }}
@stop

@section('styles')
@stop

@section('content')
<div class="row">

    <div class="col-12 col-lg-12 col-xl-12">
        {!! Form::model($main_banner, ['route'  => ['main_banner.main_banners.update', $main_banner], 'method' => 'PUT',  'enctype'=>'multipart/form-data', 'data-parsley-validate'=>'true'])
        !!}
            <div class="col-12 col-lg-12 col-xl-12">
                <div class="card card-accent-secondary">
                    <div class="card-header">
                        {{ __('Main Banner Details') }}
                    </div>
                    <div class="card-block">
                        @include('main_banner::_form')
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success">{{ __('Update') }}</button>
                        <a href="{{ route('main_banner.main_banners.show',['main_banner'=>$main_banner])}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 col-xl-3">

            </div>
        {!! Form::close() !!}
    </div>

    <div class="col-12 col-lg-4 col-xl-3">
    </div>

</div>
@stop

@section('scripts')
<!-- Parsley JS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

@include('main_banner::mainbanner_js')

@stop