@extends('appshell::layouts.default')

@section('title')
    {{ $case_study->name }}
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
                            {{ $case_study->name }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Abstract') }}</label>
                        <div>
                            {{ $case_study->abstract }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-md-12 col-xl-12">
                        <label class="form-control-label">{{ __('Link') }}</label>
                        <div>
                            {{ $case_study->link }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{route('case_study.case_study.edit',$case_study)}}"><button class="btn btn-success">{{ __('Edit') }}</button></a>
            <a href="{{route('case_study.case_study.index')}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>
@stop
