@extends('appshell::layouts.default')

@section('title')
    {{ __('What We Sells') }}
@stop

@section('content')

<div class="card card-accent-secondary">
    <div class="card-header">
        @yield('title')
        <div class="card-actionbar">
            <a href="{{ route('what_we_sell.what_we_sells.create') }}" class="btn btn-sm btn-outline-success float-right">
                <i class="zmdi zmdi-plus"></i>
                {{ __('Create What We Sell') }}
            </a>
        </div>
    </div>

    {!! Form::open(['route' => ['what_we_sell.what_we_sells.what_we_sell_reordering',$what_we_sell], 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            <div class="row">
                <div id="what_we_sells" class="list-group col-md-12">
                    @foreach($what_we_sells as $key => $whatwesell)
                        <div class="list-item mb-1">
                            <img onclick="imagepreview(this)" lazyload="on" class="list_wh" src="{{ $whatwesell->full_path }}">
                            &nbsp; &nbsp;

                            <input type="hidden" name="whatwesell_id[]" value="{{ $whatwesell->id }}" >

                            <span class="what_we_sell-description">
                                <a href="{{ route('what_we_sell.what_we_sells.show', ['what_we_sell' => $whatwesell ]) }}" target="_blank">{{ $whatwesell->title }}</a>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success" id="btnWhatWeSellReorder">{{ __('Update Order') }}</button>
        </div>

    {!! Form::close() !!}
    
</div>
@stop

@section('scripts')

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<style type="text/css">
    .list-item {
        position: relative;
        display: block;
        padding: .75rem 1.25rem;
        margin-bottom: -1px;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,.125);
    }
    .list_wh {        
        width: 150px;
        height: 150px;
    }
</style>

<script type="text/javascript">
    $(function(){

        var sortable = new Sortable(what_we_sells,{
            group: "what_we_sells",
            multiDrag: true,
            selectedClass: 'selected',
            animation: 150
        });

    });
</script>

@stop