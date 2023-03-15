@extends('appshell::layouts.default')

@section('title')
    {{ __('Marketplace Home Banners') }}
@stop

@section('content')

<div class="card card-accent-secondary">
    <div class="card-header">
        @yield('title')
        <div class="card-actionbar">
            <a href="{{ route('marketplace_main_banner.marketplace_main_banners.create') }}" class="btn btn-sm btn-outline-success float-right">
                <i class="zmdi zmdi-plus"></i>
                {{ __('Create Marketplace Home Banner') }}
            </a>
        </div>
    </div>

    {!! Form::open(['route' => ['marketplace_main_banner.marketplace_main_banners.team_member_reordering'], 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            <div class="row">
                <div id="marketplace_main_banners" class="list-group col-md-12">
                    @foreach($marketplace_main_banners as $key => $marketplace_main_banner)
                        <div class="marketplace_main_banner list-item mb-1" data-id="{{ $marketplace_main_banner->order }}">
                            <img onclick="" lazyload="on" class="list_wh" src="{{ $marketplace_main_banner->full_path }}">
                            &nbsp; &nbsp;

                            <input type="hidden" name="marketplace_main_banner_id[]" value="{{ $marketplace_main_banner->id }}" >

                            <span class="marketplace_main_banner-description">
                                <a href="{{ route('marketplace_main_banner.marketplace_main_banners.show', ['marketplace_main_banner' => $marketplace_main_banner ]) }}" target="_blank">{{ $marketplace_main_banner->caption ?? "Caption" }}</a>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Update Order') }}</button>
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
        height: auto;
    }
</style>

<script type="text/javascript">
    $(function(){

        var sortable = new Sortable(marketplace_main_banners,{
            group: "marketplace_main_banners",
            multiDrag: true,
            selectedClass: 'selected',
            animation: 150
        });

    });
</script>

@stop