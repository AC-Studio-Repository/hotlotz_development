@php
    use \App\Modules\Item\Models\Item;
@endphp
<table id="salereport-table" class="table table-striped table-hover table-responsive" style="overflow-x:auto;width:100%">
    <thead>
        <tr>
            <th width="5%">{{ __('Image of Lot') }}</th>
            <th width="5%">{{ __('Lot number') }}</th>
            <th>{{ __('Title') }}</th>
            <th>{{ __('Sold / Unsold') }}</th>
            <th>{{ __('Item Reference') }}</th>
            <th>{{ __('Seller Name') }}</th>
            <th>{{ __('Opening Bid') }}</th>
            <th>{{ __('Number of Bid') }}</th>
            <th>{{ __('Hammer Result') }}</th>
            <th>{{ __('Hammer + Premium Result') }}</th>
            <!-- <th>{{ __('Fixed Cost Sales Fee') }}</th> -->
            <th>{{ __('Sales Commission') }}</th>
            <!-- <th>{{ __('Performance Commission') }}</th> -->
            <th>{{ __('Insurance Fee') }}</th>
            <!-- <th>{{ __('Listing Fee') }}</th> -->
            <th>{{ __('Bill Number') }}</th>
            <th>{{ __('Buyer Name') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($saleReports as $item)
        <tr>
            <td>
                <img onclick="imagepreview(this)" lazyload="on" src="{{ $item['item_image'] }}" alt="..." width="150px" height="auto" full="{{ $item['item_image_full'] }}">
            </td>
            <td>
                <div class="text-muted">
                    {{  __($item['lot_number'] ?? 'N/A') }}
                </div>
            </td>
            <td>
                <span class="font-lg mb-3 font-weight-bold">
                    <a href="{{ route('item.items.show_item', [Item::find($item['item_id']), 'cataloguing'] ) }}" target="_blank">{{ __($item['item_name'])}}</a>
                </span>
            </td>
            <td>
                <div class="text-muted">
                    {{ __($item['item_status']) }}
                </div>
            </td>
            <td>
                <span class="font-lg mb-3 font-weight-bold">
                    <a href="{{ route('item.items.show_item', [Item::find($item['item_id']), 'overview'] ) }}" target="_blank">{{ __($item['item_number'])}}</a>
                </span>
            </td>
             <td>
                <span class="font-lg mb-3 font-weight-bold">
                    <a href="{{ route('customer.customers.show', $item['seller_id'] ) }}">{{ __($item['seller'])}}</a>
                </span>
            </td>
            <td>
                <div class="text-muted">
                    {{ __($item['starting_bid'])}}
                </div>
            </td>
            <td>
                <span class="font-lg mb-3 font-weight-bold">
                    <a href="{{$item['bidding_history_link']}}">{{ $item['no_of_bid'] }}</a>
                </span>
            </td>
            <td>
                <div class="text-muted">
                    {{ __($item['hammar_price'])}}
                </div>
            </td>
            <td>
                <div class="text-muted">
                    {{ __($item['total'])}}
                </div>
            </td>
            <!-- <td>
                <div class="text-muted">
                    {{ __($item['fixed_cost_sales_fee_cal'])}}
                </div>
            </td> -->
            <td>
                <div class="text-muted">
                    {{ __($item['sales_commission_cal'])}}
                </div>
            </td>
            <!-- <td>
                <div class="text-muted">
                    {{ __($item['performance_commission_cal'])}}
                </div>
            </td> -->
            <td>
                <div class="text-muted">
                    {{ __($item['insurance_fee_cal'])}}
                </div>
            </td>
             <!-- <td>
                <div class="text-muted">
                    {{ __($item['listing_fee_cal'])}}
                </div>
            </td> -->
            <td>
                <div class="text-muted">
                    @if($item['bill_id'])
                    {{ $item['bill_id'] }}
                    @endif

                </div>
            </td>
            <td>
                <span class="font-lg mb-3 font-weight-bold">
                    <a href="{{ route('customer.customers.show', $item['buyer_id'] ) }}">{{ __($item['buyer'])}}</a>
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
