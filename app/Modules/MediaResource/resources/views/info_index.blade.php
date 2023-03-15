@extends('appshell::layouts.default')

@section('title')
    {{ __('Media Resource Main') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create MediaResource')
                <a href="{{ route('media_resource.media_resources.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Display Media Resource Main') }}
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
                         @if(!$media_resource_data->isEmpty())
                            <div>
                                {{ $media_resource->caption }}
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Header') }}</label>
                         @if(!$media_resource_data->isEmpty())
                            <div>
                                {{ $media_resource->blog_header_1 }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Blog') }}</label>
                         @if(!$media_resource_data->isEmpty())
                            <div>
                                <div>{!! $media_resource->blog_1 !!}</div>
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

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Country One') }}</label>
                         @if(!$media_resource_data->isEmpty())
                            <div>
                                {{ $media_resource->contact_country_1 }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Email One') }}</label>
                         @if(!$media_resource_data->isEmpty())
                            <div>
                                {{ $media_resource->contact_email_1 }}
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Country Two') }}</label>
                         @if(!$media_resource_data->isEmpty())
                            <div>
                                {{ $media_resource->contact_country_2 }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Email Two') }}</label>
                         @if(!$media_resource_data->isEmpty())
                            <div>
                                {{ $media_resource->contact_email_2 }}
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Our Assets File') }}</label>
                         @if(!$media_resource_data->isEmpty())
                            <div>
                                <a href="{{ $media_resource->our_asset_file_path }}">File</a>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('media_resource.media_resources.editcontent')}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            <!-- <a href="{{ route('discover.about-us') }}" target="_blank" class="btn btn-link text-muted">{{ __('Link to Frontend') }}</a> -->
        </div>
    </div>
@stop