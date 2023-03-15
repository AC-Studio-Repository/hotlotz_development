@extends('appshell::layouts.default')

@section('title')
    {{ __('Footer Menu') }}
@stop


@section('content')

    <div class="card card-accent-secondary">

        <!-- <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create termsandconditions')
                <a href="{{ route('content_management.termsandconditions.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Terms and Conditions') }}
                </a>
                @endcan
            </div>

        </div> -->

        <div class="card-block">
            <table class="table table-striped table-hover">
                <tr>
                    @can('view content managements')
                         <td><a href="{{ route('content_management.termsandconditions.displayContentTandC') }}" class="btn btn-outline-primary">{{ __('TERMS & CONDITIONS') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('TERMS & CONDITIONS') }}</a></td>
                    @endcan

                </tr>
                <tr>
                    @can('view content managements')
                    <td><a href="{{ route('policy.policies.index') }}" class="btn btn-outline-primary">{{ __('POLICIES') }}</a></td>
                    @else
                         <td><a href="#" class="btn btn-outline-primary">{{ __('POLICIES') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                    <td><a href="{{ route('careers.careerss.showlist') }}" class="btn btn-outline-primary">{{ __('CAREERS') }}</a></td>
                    @else
                         <td><a href="#" class="btn btn-outline-primary">{{ __('CAREERS') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                    <td><a href="{{ route('media_resource.media_resources.showlist') }}" class="btn btn-outline-primary">{{ __('MEDIA RESOURCES') }}</a></td>
                    @else
                         <td><a href="#" class="btn btn-outline-primary">{{ __('MEDIA RESOURCES') }}</a></td>
                    @endcan
                </tr>
            </table>
        </div>
    </div>
@stop
