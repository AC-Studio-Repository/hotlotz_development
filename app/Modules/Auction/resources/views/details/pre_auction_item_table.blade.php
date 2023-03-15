@php
    use \App\Modules\Item\Models\Item;
@endphp

<div class="row">
    <div class="form-group col-md-4">
        <a href="#" onclick="download_table_as_csv('pre-auction-item-table');" class="btn btn-outline-success">Export Lots</a>
    </div>
</div>
<div class="row">
    <div class="col-md-3" style="color: red;">
        <label class="form-control-label">{{ __('Number of Items') }} ({{ $total_lots }})</label>
    </div>
    <div class="col-md-3" style="color: red;">
        <label class="form-control-label">{{ __('Total Opening Bid') }} ({{ $total_starting_bid }})</label>
    </div>
    <div class="col-md-3" style="color: red;">
        <label class="form-control-label">{{ __('Total Low Estimate') }} ({{ $total_low_estimate }})</label>
    </div>
    <div class="col-md-3" style="color: red;">
        <label class="form-control-label">{{ __('Total High Estimate') }} ({{ $total_high_estimate }})</label>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table id="pre-auction-item-table" class="table table-striped table-hover table-responsive" style="overflow-x:auto;">
            <thead>
                <tr>
                    <th width="5%">{{ __('Image of Lot') }}</th>
                    <th width="5%">{{ __('Lot number') }}</th>
                    <th width="20%">{{ __('Title') }}</th>
                    <th width="10%">{{ __('Item Reference') }}</th>
                    <th width="15%">{{ __('Seller Name') }}</th>
                    <th width="10%">&nbsp;</th>
                    <th width="10%">{{ __('Opening Bid') }}</th>
                    <th width="5%">Recently Consigned</th>
                </tr>
            </thead>
            <tbody>
                @if(count($auction_items) > 0)
                    @foreach($auction_items as $item)
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
                            @if( $item['catalogue_letter'] != '' )
                                <span style="color: {{ $item['catalogue_color'] }}">{{ __($item['catalogue_letter']) }}</span>
                            @endif
                            @if( $item['valuation_letter'] != '' )
                                <span style="color: {{ $item['valuation_color'] }}">{{ __($item['valuation_letter']) }}</span>
                            @endif
                            @if( $item['fee_structure_letter'] != '' )
                                <span style="color: {{ $item['fee_structure_color'] }}">{{ __($item['fee_structure_letter']) }}</span>
                            @endif
                            @if( $item['permission_to_sell_letter'] != '' )
                                <span style="color: {{ $item['permission_to_sell_color'] }}">{{ __($item['permission_to_sell_letter']) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-muted">
                                {{ __($item['starting_bid'])}}
                            </div>
                        </td>
                        <td>
                            <div style="overflow: hidden; white-space: nowrap;">
                                <label class="switch switch-icon switch-pill switch-primary">
                                    <input class="switch-input" data-id="{{ $item['item_id'] }}" type="checkbox" {{ ($item['recently_consigned'] == 1) ? 'checked' : ''}}>

                                    <span class="switch-label" data-on="&#xf26b;" data-off="&#xf136;"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>