@extends('appshell::layouts.default')

@section('title')
    {{ __('Professional Valuations') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        <div class="card-block">
            <div class="container">

                <!-- Image Section -->
                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        @if($banner != '')
                            <div>
                                <img onclick="imagepreview(this)" lazyload="on" alt="Item Image" src="{{ $banner }}" width="300px" height="300px">
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Image Caption') }}</label>
                         @if(!$professional_valuations_data->isEmpty())
                            <div>
                                {{ $professional_valuations->caption }}
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Header') }}</label>
                         @if(!$professional_valuations_data->isEmpty())
                            <div>
                                {{ $professional_valuations->title }}
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Blog') }}</label>
                         @if(!$professional_valuations_data->isEmpty())
                            <div>
                                <div>{!! $professional_valuations->blog !!}</div>
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
            <a href="{{route('professional_valuation.professional_valuations.editcontent')}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            <a href="{{ route('services.valuations') }}" target="_blank" class="btn btn-link text-muted">{{ __('Link to Frontend') }}</a>
        </div>
    </div>
@stop
