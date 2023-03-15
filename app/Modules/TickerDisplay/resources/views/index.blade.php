@extends('appshell::layouts.default')

@section('title')
    {{ __('Ticker Display List') }}
@stop

@section('content')

<div class="card card-accent-secondary">
    <div class="card-header">
        @yield('title')
        <div class="card-actionbar">
            <a href="{{ route('ticker_display.ticker_displays.create') }}" class="btn btn-sm btn-outline-success float-right">
                <i class="zmdi zmdi-plus"></i>
                {{ __('Create Ticker Display') }}
            </a>
        </div>
    </div>

    {!! Form::model($ticker_display, ['route' => ['ticker_display.ticker_displays.ticker_display_reordering',$ticker_display], 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            <div class="row">
                <div id="ticker_displays" class="list-group col-md-12">
                    @foreach($ticker_displays as $key => $tickerdisplay)
                        <div class="list-item mb-1">
                            <a href="{{ route('ticker_display.ticker_displays.show', ['ticker_display' => $tickerdisplay ]) }}" class="list_wh">{{ $tickerdisplay->title }}</a>
                            &nbsp; &nbsp;

                            <input type="hidden" name="tickerdisplay_id[]" value="{{ $tickerdisplay->id }}" >
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success" id="btnMainBannerReorder">{{ __('Update Order') }}</button>
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

        var sortable = new Sortable(ticker_displays,{
            group: "ticker_displays",
            multiDrag: true,
            selectedClass: 'selected',
            animation: 150
        });

    });
</script>

@stop