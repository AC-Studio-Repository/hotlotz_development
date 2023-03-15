<div class="table table-striped table-hover table-responsive" style="overflow-x:auto;">
    <table class="table table-striped" width="100%">
        <thead>
            <tr>
                <th>{{ __('Photo') }}</th>
                <th>{{ __('Item Name') }}</th>
                <th>{{ __('Item Reference Number') }}</th>
                <th>{{ __('Seller Name') }}</th>
                <th>{{ __('Low/High Estimate') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Hammer Price / Result Price') }}</th>
            </tr>
        </thead>

        <tbody>
        @foreach($items as $item)
            <tr>
                <td>
                    <div class="">
                        @php
                            $photo = \App\Modules\Item\Models\ItemImage::where('item_id',$item->id)->select('file_name','full_path')->first();
                        @endphp

                        @if(isset($photo))
                            <img onclick="imagepreview(this)" lazyload="on" src="{{ $photo->full_path }}" alt="{{$photo->file_name}}" width="60px" height="60px">
                        @endif
                    </div>
                </td>
                <td>
                    @can('view items')
                        <a href="{{ route('item.items.show_item', [$item,'cataloguing']) }}" target="_blank">{{ $item->name }}</a>
                    @else
                        {{ __($item->name) }}
                    @endcan
                </td>
                <td>
                    @can('view items')
                        <a href="{{ route('item.items.show_item', [$item,'overview']) }}" target="_blank">{{ $item->item_number }}</a>
                    @else
                        {{ __($item->item_number) }}
                    @endcan

                </td>
                <td>
                    <div class="">
                        @can('view customers')
                            <a href="{{ route('customer.customers.show', $item->customer) }}" target="_blank">{{ isset($item->customer) ? $item->customer->fullname : '_' }}</a>
                        @else
                            {{ isset($item->customer) ? $item->customer->fullname : '_' }}
                        @endcan

                    </div>
                </td>
                <td>
                    <div class="">
                        ${{ number_format($item->low_estimate) }}/${{ number_format($item->high_estimate) }}
                    </div>
                </td>
                <td>
                    <div class="mt-2">
                        <span class="badge badge-pill badge-success">{{ __($item->status) }}</span>
                    </div>
                </td>
                <td>
                    @if($item->buyer_id > 0)
                    <div class="mb-3">
                        Hammer Price <br>
                        ${{ ($item->sold_price != null)?number_format($item->sold_price):0.00 }}
                    </div>
                    <div>
                        Result Price <br>
                        ${{ (isset($item->total) && $item->total != null)?number_format($item->total,2,'.',','):0.00 }}
                    </div>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
