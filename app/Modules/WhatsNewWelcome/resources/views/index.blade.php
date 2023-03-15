@extends('appshell::layouts.default')

@section('title')
    {{ __("What's New Welcomes") }}
@stop

@section('content')

<div class="card card-accent-secondary">
    @php
        $route = 'whats_new_welcome.whats_new_welcomes.store';
        $method = "POST";
        if($id > 0){
            $route = ['whats_new_welcome.whats_new_welcomes.update', $whats_new_welcome];
            $method = "PUT";
        }
    @endphp

    {!! Form::open(['route' => $route, 'method' => $method, 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row', 'data-parsley-validate'=>'true']) !!}

        <div class="col-12 col-lg-12 col-xl-12">
            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __("What's New Welcome Details") }}
                </div>
                <div class="card-block">
                    <input type="hidden" name="article_one_id" value="{{ $id }}">
                    @include('whats_new_welcome::_form')
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Save') }}</button>
                    <a href="{{ route('home_page.home_pages.index')}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>

    {!! Form::close() !!}
    
</div>
@stop

@section('scripts')
<!-- Parsley JS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

@include('whats_new_welcome::whatsnewwelcome_js')

@stop