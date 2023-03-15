@extends('appshell::layouts.default')

@section('title')
    {{ __('Sell With Us Main') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create AuctionCms')
                <a href="{{ route('sell_with_us.sell_with_uss.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Display Sell With Us Main') }}
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
                                <img onclick="imagepreview(this)" lazyload="on" src="{{ $banner }}" width="300px" height="300px">
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Header') }}</label>
                         @if(!$sell_with_us_data->isEmpty())
                            <div>
                                {{ $sell_with_us->blog_header_1 }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Blog') }}</label>
                         @if(!$sell_with_us_data->isEmpty())
                            <div>
                                <div>{!! $sell_with_us->blog_1 !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                 <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Blog Header One') }}</label>
                         @if(!$sell_with_us_data->isEmpty())
                            <div>
                                {{ $sell_with_us->blog_header_2 }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Blog One') }}</label>
                         @if(!$sell_with_us_data->isEmpty())
                            <div>
                                <div>{!! $sell_with_us->blog_2 !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('sell_with_us.sell_with_uss.editcontent')}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>
@stop