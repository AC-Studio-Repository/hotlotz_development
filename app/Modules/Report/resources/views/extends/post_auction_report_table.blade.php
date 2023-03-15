
@php
    use \App\Modules\Item\Models\Item;
@endphp
<div class="row">
    <div class="col-md-12">
        <div class="btn-group float-right ml-3">
          <button type="button" class="btn btn-outline-success dropdown-toggle mb-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Generate Report
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" onclick="download_table_as_csv('auction_unsold_report');" href="#">CSV</a>
          </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-block">
        <div class="row">
            <div class="col-md-4">
                <label class="form-control-label">{{ __('Filter By Seller') }} <span style="color:red;">*</span></label>
                <div class="form-group">
                    <select name="filter_sellers" id="filter_seller" class="select2 form-control" onchange="loadTable()">
                        <option value="">{{ __('Search & Select Seller') }}</option>
                        @foreach($customers as $customer)
                         <option value="{{ $customer->id }}" {{ $seller == $customer->id ? 'selected' : ''}}>{{ $customer->search_full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="form-control-label">{{ __('Report Detail') }}</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <table id="auction_unsold_report" class="table table-striped table-hover table-responsive" style="overflow-x:auto;width:100%">
                    <thead>
                        <tr>
                            <th width="5%">{{ __('Image of Lot') }}</th>
                            <th width="5%">{{ __('Lot number') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Lifecycle next stage') }}</th>
                            <th>{{ __('Sold / Unsold') }}</th>
                            <th>{{ __('Item Reference') }}</th>
                            <th>{{ __('Seller Name') }}</th>
                            <th>{{ __('Opening Bid') }}</th>
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
                                    {{ __(Item::find($item['item_id'])->status) }}
                                </div>
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

                            <!-- <td>
                                <div class="text-muted">
                                    {{ __($item['fixed_cost_sales_fee_cal'])}}
                                </div>
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
