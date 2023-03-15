@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $whats_new_bid_barometer->name }}
@stop

@section('styles')
@stop

@section('content')
<div class="row">

    <div class="col-12 col-lg-12 col-xl-12">
        {!! Form::model($whats_new_bid_barometer, ['route'  => ['whats_new_bid_barometer.whats_new_bid_barometers.update', $whats_new_bid_barometer], 'method' => 'PUT',  'enctype'=>'multipart/form-data', 'data-parsley-validate'=>'true'])
        !!}
            <div class="col-12 col-lg-12 col-xl-12">
                <div class="card card-accent-secondary">
                    <div class="card-header">
                        {{ __("What's New Bid Barometer Details") }}
                    </div>
                    <div class="card-block">
                        @include('whats_new_bid_barometer::_form')
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success">{{ __('Update') }}</button>
                        <a href="{{ route('whats_new_bid_barometer.whats_new_bid_barometers.show',['whats_new_bid_barometer'=>$whats_new_bid_barometer])}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
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

@include('whats_new_bid_barometer::whatsnewarticleone_js')

@stop