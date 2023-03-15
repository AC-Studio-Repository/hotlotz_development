@extends('appshell::layouts.default')

@section('title')
    {{ $internal_advert->name }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        <div class="card-block">
            <div class="container">

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title') }}</label>
                        <div>
                            {{ $internal_advert->name }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Tagline') }}</label>
                        <div>
                            {{ $internal_advert->description }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Link') }}</label>
                        <div>
                            {{ $internal_advert->url }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('internal_advert.internal_advert.edit',$internal_advert)}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="{{route('internal_advert.internal_advert.index')}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>
@stop
