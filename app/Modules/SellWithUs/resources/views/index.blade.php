@extends('appshell::layouts.default')

@section('title')
    {{ __('Header') }}
@stop


@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
            <table class="table table-striped table-hover">
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('sell_with_us.sell_with_uss.infopage') }}" class="btn btn-outline-primary">{{ __('Sell With Us MAIN PAGE') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('Sell With Us MAIN PAGE') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('sell_with_us.sell_with_uss.list') }}" class="btn btn-outline-primary">{{ __('Sell With Us LIST') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('Sell With Us LIST') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('auction_main_page.auction_main_pages.auctionResultsIndex') }}" class="btn btn-outline-primary">{{ __('Auction Results Main Page') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('Auction Results Main Page') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('auction_main_page.auction_main_pages.pastCataloguesIndex') }}" class="btn btn-outline-primary">{{ __('Past Catalogues Main Page') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('Past Catalogues Main Page') }}</a></td>
                    @endcan
                </tr>
            </table>
        </div>
    </div>
@stop