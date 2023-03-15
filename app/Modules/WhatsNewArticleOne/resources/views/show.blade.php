@extends('appshell::layouts.default')

@section('title')
    {{ __("What's New Article One Details") }}
@stop

@section('content')
    <div class="card">
        <div class="card-block">
            <a href="{{ route('whats_new_article_one.whats_new_article_ones.index') }}" class="btn btn-outline-info">{{ __("Edit What's New Article One") }}</a>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @include('whats_new_article_one::_details')
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
