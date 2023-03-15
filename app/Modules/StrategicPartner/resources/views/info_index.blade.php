@extends('appshell::layouts.default')

@section('title')
    {{ __('Strategic Partners Main') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create StrategicPartner')
                <a href="{{ route('strategic_partner.strategic_partners.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Display Strategic Partners Main') }}
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
                         @if($banner)
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
                         @if(!$strategic_partner_data->isEmpty())
                            <div>
                                {{ $strategic_partner->caption }}
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Header') }}</label>
                         @if(!$strategic_partner_data->isEmpty())
                            <div>
                                {{ $strategic_partner->blog_header_1 }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Blog') }}</label>
                         @if(!$strategic_partner_data->isEmpty())
                            <div>
                                <div>{!! $strategic_partner->blog_1 !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                @foreach($blogs as $key=>$blog)
                @php
                    $index = $key + 1;
                @endphp
                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Blog Header '.$index) }}</label>
                        <div>
                            {{ $blog->title }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Blog '.$index) }}</label>
                        <div>
                            <div>{!! $blog->blog !!}</div>
                        </div>
                    </div>
                </div>
                <hr>
                @endforeach
            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('strategic_partner.strategic_partners.editcontent')}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            <!-- <a href="{{ route('discover.about-us') }}" target="_blank" class="btn btn-link text-muted">{{ __('Link to Frontend') }}</a> -->
        </div>
    </div>
@stop