@extends('appshell::layouts.default')

@section('title')
    {{ __('Banners') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
                <table class="table table-hover" style="border: 1px solid #c9d0d0;">
                        <tr>
                        @can('view content managements')
                            <td><a href="{{ route('home_page.home_pages.main_banner_index') }}" class="btn btn-outline-primary">{{ __('Main Banner') }}</a></td>
                        @else
                             <td><a href="#" class="btn btn-outline-primary">{{ __('Main Banner') }}</a></td>
                        @endcan
                        </tr>
                        <tr>
                        @can('view content managements')
                            <td><a href="{{ route('main_banner.main_banners.index') }}" class="btn btn-outline-primary">{{ __('Main Banner (New)') }}</a></td>
                        @else
                             <td><a href="#" class="btn btn-outline-primary">{{ __('Main Banner (New)') }}</a></td>
                        @endcan
                        </tr>
                        <tr>
                         @can('view content managements')
                            <td><a href="{{ route('home_page.home_pages.marketplace_banner_index') }}" class="btn btn-outline-primary">{{ __('Marketplace Banner') }}</a></td>
                         @else
                             <td><a href="#" class="btn btn-outline-primary">{{ __('Marketplace Banner') }}</a></td>
                         @endcan

                        </tr>
                        <tr>
                         @can('view content managements')
                            <td><a href="{{ route('marketplace_banner.marketplace_banners.index') }}" class="btn btn-outline-primary">{{ __('Marketplace Banner (New)') }}</a></td>
                         @else
                             <td><a href="#" class="btn btn-outline-primary">{{ __('Marketplace Banner (New)') }}</a></td>
                         @endcan

                        </tr>
                </table>
        </div>
    </div>

@stop
