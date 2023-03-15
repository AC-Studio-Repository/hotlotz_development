@extends('appshell::layouts.default')

@section('styles')
@stop

@section('title')
    {{ __('Editing') }} {{ $auction->title }}
@stop

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header">
        {{ __('Auction Details') }}
    </div>

    {!! Form::model($auction, ['route' => ['auction.auctions.update', $auction], 'method' => 'PUT', 'data-parsley-validate'=>'true', 'autocomplete' => 'off','files' => 'true', 'enctype'=>'multipart/form-data']) !!}

        <div class="card-block">
            @include('auction::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-outline-success">{{ __('Save') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-outline-danger">{{ __('Cancel') }}</a>
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

@include('auction::auctionjs')
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

    var _token = $('input[name="_token"]').val();
    $("#btn_publish").click(function(){

        $.ajax({
            url: "/manage/auctions/publishAuction",
            type: 'post',
            data: "id="+{!! json_encode($auction->id) !!}+"&_token="+_token,
            dataType: 'json',
            async: false,
            success: function(response) {
                if(response.status == 1){
                    $("#btn_publish").hide();
                }
                alert(response.message);
                location.reload(true);
            }
        });
    });

</script>
@stop
