@extends('appshell::layouts.default')

@section('title')
    {{ __('Home Page') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
                <table class="table table-hover" style="border: 1px solid #c9d0d0;">
                        <tr>
                            @can('view content managements')
                                <td><a href="{{ route('home_page.home_pages.main_banner_list') }}" class="btn btn-outline-primary">{{ __('Banners') }}</a></td>
                             @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __('Banners') }}</a></td>
                            @endcan
                        </tr>
                        <tr>
                            @can('view content managements')
                                <td><a href="{{ route('home_page.home_pages.showtestimonial') }}" class="btn btn-outline-primary">{{ __('Testimonial') }}</a></td>
                            @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __('Testimonial') }}</a></td>
                            @endcan
                        </tr>
                        <tr>
                            @can('view content managements')
                                <td><a href="{{ route('home_page_random_text.home_page_random_texts.index') }}" class="btn btn-outline-primary">{{ __('Ticker Display') }}</a></td>
                             @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __('Ticker Display') }}</a></td>
                            @endcan
                        </tr>
                        <tr>
                            @can('view content managements')
                                <td><a href="{{ route('ticker_display.ticker_displays.index') }}" class="btn btn-outline-primary">{{ __('Ticker Display (New)') }}</a></td>
                             @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __('Ticker Display (New)') }}</a></td>
                            @endcan
                        </tr>
                        <tr>
                            @can('view content managements')
                                <td><a href="{{ route('whats_new_article_one.whats_new_article_ones.index') }}" class="btn btn-outline-primary">{{ __("What's New") }}</a></td>
                             @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __("What's New") }}</a></td>
                            @endcan
                        </tr>
                        <tr>
                            @can('view content managements')
                                <td><a href="{{ route('whats_new_welcome.whats_new_welcomes.index') }}" class="btn btn-outline-primary">{{ __("What's New Welcome") }}</a></td>
                             @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __("What's New Welcome") }}</a></td>
                            @endcan
                        </tr>
                        <tr>
                            @can('view content managements')
                                <td><a href="{{ route('whats_new_bid_barometer.whats_new_bid_barometers.index') }}" class="btn btn-outline-primary">{{ __("What's New Bid Barometer") }}</a></td>
                             @else
                                <td><a href="#" class="btn btn-outline-primary">{{ __("What's New Bid Barometer") }}</a></td>
                            @endcan
                        </tr>
                </table>
        </div>
    </div>

@stop
