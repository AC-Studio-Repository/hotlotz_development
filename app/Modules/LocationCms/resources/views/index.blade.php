@extends('appshell::layouts.default')

@section('title')
    {{ __('Location') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <!-- <div class="card-actionbar">
                <a href="{{ route('location_cms.location_cmss.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Display Location') }}
                </a>
            </div> -->

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
                        <label class="form-control-label">{{ __('Image Caption') }}</label>
                         @if(!$location_cms_data->isEmpty())
                            <div>
                                {{ $location_cms->caption }}
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Header') }}</label>
                         @if(!$location_cms_data->isEmpty())
                            <div>
                                {{ $location_cms->title_header }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Blog') }}</label>
                         @if(!$location_cms_data->isEmpty())
                            <div>
                                <div>{!! $location_cms->title_blog !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Direction Header') }}</label>
                         @if(!$location_cms_data->isEmpty())
                            <div>
                                {{ $location_cms->direction_header }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Direction Blog') }}</label>
                         @if(!$location_cms_data->isEmpty())
                            <div>
                                <div>{!! $location_cms->direction_blog !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Saleroom Details') }}</label>
                         @if(!$location_cms_data->isEmpty())
                            <div>
                                <div>{!! $location_cms->saleroom_details !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Opening Times') }}</label>
                    </div>
                </div>

                @foreach($days as $day)
                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __($day['label']) }}</label>
                         @if(!$location_cms_data->isEmpty())
                            <div>
                                {{ $day['value'] }}
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('location_cms.location_cmss.editcontent')}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            <!-- <a href="{{ route('services.marketplace') }}" target="_blank" class="btn btn-link text-muted">{{ __('Link to Frontend') }}</a> -->
        </div>
    </div>
@stop