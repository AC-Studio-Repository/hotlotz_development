<div class="card">
    <div class="card-block">
        <div class="row">
            <div class="col-md-12">
                <label class="form-control-label">{{ __('Lots') }}</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Item Number') }}</th>
                            <th>{{ __('Item Name') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Permission To Sell') }}</th>
                            <th>{{ __('Seller Name') }}</th>
                            <th>{{ __('Category') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($lot_list) > 0)
                            @foreach($lot_list as $lot)
                            <tr>
                                <td>
                                    <div class="text-muted">
                                        @php
                                            $item = \App\Modules\Item\Models\Item::find($lot['item_id']);
                                        @endphp
                                        @if($item)
                                            <a href="{{ route('item.items.show', $item) }}" target="_blank">{{ $lot['item_number'] }}</a>
                                        @else
                                            <a href="#">{{ $lot['item_number'] }}</a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $lot['item_name'] }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $lot['item_status'] }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ ($lot['permission_to_sell'] == 'Y')?'Yes':'No' }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $customer = \App\Modules\Customer\Models\Customer::find($lot['seller_id']);
                                    @endphp
                                    @if($customer)
                                        <a href="{{ route('customer.customers.show', $customer) }}">{{ $customer->fullname }}</a>
                                    @else
                                        <a href="#">{{ $lot['seller_name'] }}</a>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $lot['category'] }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr><td colspan="6">No data!</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>