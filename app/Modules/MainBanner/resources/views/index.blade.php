@extends('appshell::layouts.default')

@section('title')
    {{ __('Main Banners') }}
@stop

@section('content')

<div class="card card-accent-secondary">
    <div class="card-header">
        @yield('title')
        <div class="card-actionbar">
            <a href="{{ route('main_banner.main_banners.create') }}" class="btn btn-sm btn-outline-success float-right">
                <i class="zmdi zmdi-plus"></i>
                {{ __('Create Main Banner') }}
            </a>
        </div>
    </div>

    {!! Form::model($main_banner, ['route' => ['main_banner.main_banners.main_banner_reordering',$main_banner], 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            <div class="row">
                <div id="main_banners" class="list-group col-md-12">
                    @foreach($main_banners as $key => $mainbanner)
                        <div class="mainbanner list-item mb-1" data-id="{{ $mainbanner->order }}">
                            <img onclick="" lazyload="on" class="list_wh" src="{{ $mainbanner->full_path }}">
                            &nbsp; &nbsp;

                            <input type="hidden" name="mainbanner_id[]" value="{{ $mainbanner->id }}" >

                            <span class="mainbanner-description">
                                <a href="{{ route('main_banner.main_banners.show', ['main_banner' => $mainbanner ]) }}" target="_blank">{{ $mainbanner->main_title }}</a>
                            </span>
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

        var sortable = new Sortable(main_banners,{
            group: "main_banners",
            multiDrag: true,
            selectedClass: 'selected',
            animation: 150
        });

    });
</script>

@stop