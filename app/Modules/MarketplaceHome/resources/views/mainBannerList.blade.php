@extends('appshell::layouts.default')

@section('title')
    {{ __('Marketplace') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
                <table class="table table-hover" style="border: 1px solid #c9d0d0;">
                        <tr>
                            @can('view content managements')
                                <td><a href="{{ route('marketplace_home_banner.marketplace_home_banners.index') }}" class="btn btn-outline-primary">{{ __('Home Banner') }}</a></td>
                             @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __('Home Banner') }}</a></td>
                            @endcan
                        </tr>
                        <tr>
                            @can('view content managements')
                                <td><a href="{{ route('marketplace_main_banner.marketplace_main_banners.index') }}" class="btn btn-outline-primary">{{ __('Home Banner (New)') }}</a></td>
                             @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __('Home Banner (New)') }}</a></td>
                            @endcan
                        </tr>
                        <tr>
                            @can('view content managements')
                            <td><a href="{{ route('marketplace_home.marketplace_homes.substainable_banner_index') }}" class="btn btn-outline-primary">{{ __('Sustainable Banner') }}</a></td>
                            @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __('Sustainable Banner') }}</a></td>
                            @endcan
                        </tr>
                        <tr>
                            @can('view content managements')
                                <td><a href="{{ route('marketplace_home.marketplace_homes.collaboration_banner_index') }}" class="btn btn-outline-primary">{{ __('Collaboration Banners') }}</a></td>
                            @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __('Collaboration Banner') }}</a></td>
                            @endcan
                        </tr>
                        <tr>
                            @can('view content managements')
                            <td><a href="{{ route('marketplace_home.marketplace_homes.editcontent') }}" class="btn btn-outline-primary">{{ __('Collaboration Page') }}</a></td>
                            @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __('Collaboration Page') }}</a></td>
                            @endcan
                        </tr>
                        <tr>
                            @can('view content managements')
                            <td><a href="{{ route('marketplace_home.marketplace_homes.itemDetailPolicyCms') }}" class="btn btn-outline-primary">{{ __('Collection & Shipping, One Tree Planted, Sale Policy') }}</a></td>
                            @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __('Collection & Shipping, One Tree Planted, Sale Policy') }}</a></td>
                            @endcan
                        </tr>
                </table>
        </div>
    </div>

@stop
