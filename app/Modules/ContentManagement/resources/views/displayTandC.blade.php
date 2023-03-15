@extends('appshell::layouts.default')

@section('title')
    {{ __('Terms and Conditions') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <!-- <div class="card-actionbar">
                <a href="{{ route('content_management.termsandconditions.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Terms and Conditions') }}
                </a>
            </div> -->

        </div>

        <div class="card-block">
            <div class="container">
                <div class="row mt-50">
                    <!-- <div id="summernote">{!! json_decode($content_managements) !!}</div> -->
                    <div id="summernote">{!! $content_managements !!}</div>
                    <!-- <button class="btn btn-info" onclick="show_content();">Show Content</button> -->
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('content_management.termsandconditions.editcontent')}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            <a href="{{ route('terms') }}" target="_blank" class="btn btn-link text-muted">{{ __('Link to Frontend') }}</a>
        </div>
    </div>
@stop
