@extends('appshell::layouts.default')

@section('title')
    {{ __('Services') }}
@stop


@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
            <table class="table table-striped table-hover">
                <tr>
                    @can('view content managements')
                     <td><a href="{{ route('what_we_sell.what_we_sells.showlist') }}" class="btn btn-outline-primary">{{ __('WHAT WE SELL') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('WHAT WE SELL') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                    <td><a href="{{ route('auction_cms.auction_cmss.index') }}" class="btn btn-outline-primary">{{ __('AUCTIONS') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('AUCTIONS') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('marketplace_cms.marketplace_cmss.index') }}" class="btn btn-outline-primary">{{ __('MARKETPLACE') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('MARKETPLACE') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('private_collections.private_collectionss.index') }}" class="btn btn-outline-primary">{{ __('PRIVATE COLLECTIONS') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('PRIVATE COLLECTIONS') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                         <td><a href="{{ route('home_content.home_contents.index') }}" class="btn btn-outline-primary">{{ __('HOME CONTENTS') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('HOME CONTENTS') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('business_seller.business_sellers.index') }}" class="btn btn-outline-primary">{{ __('BUSINESS SELLERS') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('BUSINESS SELLERS') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                         <td><a href="{{ route('professional_valuation.professional_valuations.contentIndex') }}" class="btn btn-outline-primary">{{ __('PROFESSIONAL VALUATIONS') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('PROFESSIONAL VALUATIONS') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('hotlotz_concierge.hotlotz_concierges.index') }}" class="btn btn-outline-primary">{{ __('Estate Services') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('Estate Services') }}</a></td>
                    @endcan
                </tr>
                <tr>
                    @can('view content managements')
                        <td><a href="{{ route('shipping_and_storage.shipping_and_storages.index') }}" class="btn btn-outline-primary">{{ __('Collection & Shipping') }}</a></td>
                    @else
                        <td><a href="#" class="btn btn-outline-primary">{{ __('Collection & Shipping') }}</a></td>
                    @endcan
                </tr>
            </table>
        </div>
    </div>
@stop
