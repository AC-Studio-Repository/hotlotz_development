@extends('appshell::layouts.default')

@section('title')
    {{ __('Highlight List') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Highlight Reordering') }}
        <div class="card-actionbar">
            <a href="{{ route('what_we_sell.what_we_sells.highlight_create', $what_we_sell) }}" class="btn btn-sm btn-outline-success float-right">
                <i class="zmdi zmdi-plus"></i>
                {{ __('Create Highlight') }}
            </a>
        </div>
    </div>

    {!! Form::model($what_we_sell, ['route' => ['what_we_sell.what_we_sells.highlight_reordering',$what_we_sell], 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            <div class="row">
                <div id="highlights" class="list-group col-md-12">
                    @foreach($highlights as $key => $highlight)
                        <div class="list-item mb-1">
                            <img onclick="imagepreview(this)" lazyload="on" class="list_wh" src="{{ $highlight->full_path }}">
                            &nbsp; &nbsp;

                            <input type="hidden" name="highlight_id[]" value="{{ $highlight->id }}" >

                            <span class="wws_highlight-description">
                                <a href="{{ route('what_we_sell.what_we_sells.highlight_edit',[$what_we_sell, $highlight]) }}" target="_blank">{{ $highlight->title }}</a>
                            </span>
                            
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success" id="btnLotReorder">{{ __('Highlight Reorder') }}</button>
            <a href="{{ route('what_we_sell.what_we_sells.show',['what_we_sell'=>$what_we_sell])}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
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

        var sortable = new Sortable(highlights,{
            group: "highlights",
            multiDrag: true,
            selectedClass: 'selected',
            animation: 150
        });

    });
</script>
@stop