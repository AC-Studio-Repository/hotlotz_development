@extends('appshell::layouts.default')

@section('title')
    {{ __('How To Buy') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create HowToBuy')
                <a href="{{ route('how_to_buy.how_to_buys.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Display How To Buy') }}
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
                         @if(!$how_to_buy_data->isEmpty())
                            <div>
                                {{ $how_to_buy->caption }}
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Header') }}</label>
                         @if(!$how_to_buy_data->isEmpty())
                            <div>
                                {{ $how_to_buy->blog_header_1 }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Blog') }}</label>
                         @if(!$how_to_buy_data->isEmpty())
                            <div>
                                <div>{!! $how_to_buy->blog_1 !!}</div>
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
                        <label class="form-control-label">{{ __('Uploaded File') }}</label>
                         @if(!$how_to_buy_data->isEmpty())
                            <div>
                                <a href="{{ $how_to_buy->uploaded_filen_path }}">{{ $how_to_buy->uploaded_filename }}</a>
                            </div>
                        @endif
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('how_to_buy.how_to_buys.editcontent')}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            <a href="{{ route('discover.how-to-buy') }}" target="_blank" class="btn btn-link text-muted">{{ __('Link to Frontend') }}</a>
        </div>
    </div>
@stop