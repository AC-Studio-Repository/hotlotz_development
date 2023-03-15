@extends('appshell::layouts.default')

@section('title')
    {{ __('Our Team Page') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create OurTeam')
                <a href="{{ route('our_team.our_teams.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Display Our Team Main') }}
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
                         @if(!$our_team_data->isEmpty())
                            <div>
                                {{ $our_team->caption }}
                            </div>
                        @endif
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Header') }}</label>
                         @if(!$our_team_data->isEmpty())
                            <div>
                                {{ $our_team->title_header }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Title Blog') }}</label>
                         @if(!$our_team_data->isEmpty())
                            <div>
                                <div>{!! $our_team->title_blog !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('our_team.our_teams.editcontent')}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="#" onclick="history.back()" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>
@stop
