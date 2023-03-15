@extends('appshell::layouts.default')

@section('title')
    {{ __('Past Catalogues Main') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create AuctionMainPage')
                <a href="{{ route('past_catalogues.past_cataloguess.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Display Past Catalogues Main') }}
                </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <div class="container">

                <!-- Image Section -->
                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Banner Image') }}</label>
                         @if($banner != '')
                            <div id="old_image">
                                <img onclick="imagepreview(this)" lazyload="on" src = "{{ $banner }}" width="300px" height="300px" />
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Image Caption') }}</label>
                         @if(!$past_catalogues_data->isEmpty())
                            <div>
                                {{ $past_catalogues->caption }}
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Header') }}</label>
                         @if(!$past_catalogues_data->isEmpty())
                            <div>
                                {{ $past_catalogues->title_header }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Blog') }}</label>
                         @if(!$past_catalogues_data->isEmpty())
                            <div>
                                <div>{!! $past_catalogues->title_blog !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('auction_main_page.auction_main_pages.editPastCataloguesContent')}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            <!-- <a href="{{ route('services.auction') }}" target="_blank" class="btn btn-link text-muted">{{ __('Link to Frontend') }}</a> -->
        </div>
    </div>
@stop