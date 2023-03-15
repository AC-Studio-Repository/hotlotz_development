@extends('appshell::layouts.default')

@section('title')
    {{ __('Home Content') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create HomeContent')
                <a href="{{ route('home_content.home_contents.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Display Home Content') }}
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
                        <label class="form-control-label">{{ __('Image Caption') }}</label>
                         @if(!$home_content_data->isEmpty())
                            <div>
                                {{ $home_content->caption }}
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Header') }}</label>
                         @if(!$home_content_data->isEmpty())
                            <div>
                                {{ $home_content->blog_header_1 }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Blog') }}</label>
                         @if(!$home_content_data->isEmpty())
                            <div>
                                <div>{!! $home_content->blog_1 !!}</div>
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
                        <label class="form-control-label">{{ __('Key Contact One') }}</label>
                        <div>
                            <div>{!! $key_contact_1 !!}</div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Key Contact Two') }}</label>
                        <div>
                            <div>{!! $key_contact_2 !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('home_content.home_contents.editcontent')}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            <a href="{{ route('services.home-content') }}" target="_blank" class="btn btn-link text-muted">{{ __('Link to Frontend') }}</a>
        </div>
    </div>
@stop