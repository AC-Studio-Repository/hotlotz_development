@extends('appshell::layouts.default')

@section('styles')
@stop

@section('title')
    {{ __('Create new auction') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Enter Auction Details') }}

    </div>
    {!! Form::model($auction, ['route' => 'auction.auctions.store', 'data-parsley-validate'=>'true', 'autocomplete' => 'off','files' => 'true', 'enctype'=>'multipart/form-data']) !!}

        <div class="card-block">
            @include('auction::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create auction') }}</button>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop

@section('scripts')
<!-- ### Additional CSS ### -->
<!-- Parsley CSS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
<!-- Parsley JS -->
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<!-- ### Additional JS ### -->
<script src="{{asset('custom/js/pickadate/lib/picker.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/picker.date.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/picker.time.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/legacy.js')}}"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">

<script>
    new Vue({
        el: '#app',
        data: {
            customerType: '1',
            formType: '{{ request()->segment(count(request()->segments())) }}',
        }
    });

    $(".select2").select2({
        placeholder: 'Choose a timezone..',
    });
</script>

@include('auction::auctionjs')
@stop
