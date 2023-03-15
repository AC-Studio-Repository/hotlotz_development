@extends('appshell::layouts.default')

@section('styles')
@stop

@section('title')
    {{ __('Edit Highlight') }}
@stop

@section('content')

    {!! Form::open(['route' => ['what_we_sell.what_we_sells.highlight_update', [ $what_we_sell, $highlight] ], 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row', 'data-parsley-validate'=>'true']) !!}

        <div class="col-12 col-lg-12 col-xl-12">
            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __('Highlight Details') }}
                </div>
                <div class="card-block">
                    @include('what_we_sells::highlight_detail')
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Update') }}</button>
                    <a href="{{ route('what_we_sell.what_we_sells.highlight_list', $what_we_sell) }}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
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

@include('what_we_sells::whatwesell_js')

@stop