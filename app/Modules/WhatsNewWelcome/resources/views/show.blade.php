@extends('appshell::layouts.default')

@section('title')
    {{ __("What's New Welcome Details") }}
@stop

@section('content')
    <div class="card">
        <div class="card-block">
            <a href="{{ route('whats_new_welcome.whats_new_welcomes.index') }}" class="btn btn-outline-info">{{ __("Edit What's New Welcome") }}</a>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @include('whats_new_welcome::_details')
        </div>
    </div>

@stop

@section('scripts')

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $(function(){

        
    });

</script>

@stop
