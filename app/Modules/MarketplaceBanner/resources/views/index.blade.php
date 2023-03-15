@extends('appshell::layouts.default')

@section('title')
    {{ __('Marketplace Banners') }}
@stop

@section('content')

<div class="card card-accent-secondary">
    <div class="card-header">
        @yield('title')
        <div class="card-actionbar">
            <a href="{{ route('marketplace_banner.marketplace_banners.create') }}" class="btn btn-sm btn-outline-success float-right">
                <i class="zmdi zmdi-plus"></i>
                {{ __('Create Marketplace Banner') }}
            </a>
        </div>
    </div>

    @if(count($marketplace_banners) > 0)
        {!! Form::model($marketplace_banner, ['route' => ['marketplace_banner.marketplace_banners.marketplace_banner_reordering',$marketplace_banner], 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

            <div class="card-block">
                <div class="row">
                    <div id="marketplace_banners" class="list-group col-md-12">
                        @foreach($marketplace_banners as $key => $marketplacebanner)
                            <div class="list-item mb-1" data-id="{{ $marketplacebanner->order }}">
                                <img onclick="" lazyload="on" class="list_wh" src="{{ $marketplacebanner->full_path }}">
                                &nbsp; &nbsp;

                                <input type="hidden" name="marketplacebanner_id[]" value="{{ $marketplacebanner->id }}" >

                                <span class="marketplacebanner-description">
                                    <a href="{{ route('marketplace_banner.marketplace_banners.show', ['marketplace_banner' => $marketplacebanner ]) }}" target="_blank">Detail Page</a>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-success" id="btnMarketplaceBannerReorder">{{ __('Update Order') }}</button>
            </div>

        {!! Form::close() !!}
    @endif
    
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

        var sortable = new Sortable(marketplace_banners,{
            group: "marketplace_banners",
            multiDrag: true,
            selectedClass: 'selected',
            animation: 150
        });

    });
</script>

@stop