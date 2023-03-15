@php
    use \App\Modules\Item\Models\Item;
    use \App\Modules\Item\Models\ItemImage;
    use \App\Modules\Customer\Models\Customer;
@endphp

@foreach($items as $item)
    <tr>
        <td>
            {{ Form::checkbox('item_id[]', $item->id, false, [
                    'class' => "item_id",
                ])
            }}
        </td>
        <td>
            <div class="">
                @php
                    $photo = ItemImage::where('item_id',$item->id)->first();
                @endphp

                @if(isset($photo))
                    <img onclick="imagepreview(this)" lazyload="on" src="{{ $photo->image_path }}" width="150px" height="auto" full="{{$photo->full_path}}">
                @endif
            </div>
        </td>
        <td>
            @can('view items')
                <a href="{{ route('item.items.show_item', [$item,'overview']) }}" target="_blank">{{ $item->item_number }}</a>
            @else
                {{ __($item->item_number) }}
            @endcan
        </td>
        <td>
            @can('view items')
                <a href="{{ route('item.items.show_item', [$item,'cataloguing']) }}" target="_blank">{{ $item->name }}</a>
            @else
                {{ __($item->name) }}
            @endcan
        </td>
        <td>
            {{ isset($item->category)?$item->category->name:'' }}
        </td>
        <td>
            @php
                $customer = Customer::find($item->customer_id);
            @endphp
            @if($item->customer_id > 0 && $customer != null)
                <div class="">
                    @can('view customers')
                        <a href="{{ route('customer.customers.show', $customer) }}" target="_blank">{{ $customer->fullname }}</a>
                    @else
                        {{ $customer->fullname }}
                    @endcan
                </div>
            @else
                {{ "_" }}
            @endif
        </td>
        <td>
            @php
                $buy_now_price = 0;
                if(isset($item->price)){
                    $buy_now_price = $item->price;
                }
            @endphp
            ${{ number_format($buy_now_price) }}
        </td>
        <td>
            <div class="mb-4">
                @if($item->status == Item::_SOLD_)
                    <h6><span class="badge badge-pill badge-warning">{{ __($item->status) }}</span></h6>
                @elseif($item->status == Item::_PAID_)
                    <h6><span class="badge badge-pill badge-info">{{ __($item->status) }}</span></h6>
                @elseif($item->status == Item::_SETTLED_)
                    <h6><span class="badge badge-pill badge-secondary">{{ __($item->status) }}</span></h6>
                @else
                    <h6><span class="badge badge-pill badge-success">{{ __($item->status) }}</span></h6>
                @endif

                @if( in_array($item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_, Item::_WITHDRAWN_, Item::_STORAGE_, Item::_UNSOLD_]) && in_array($item->tag, ['in_storage',null]) )
                    <h6><span class="badge badge-pill badge-danger">{{ __('In Storage') }}</span></h6>
                @endif
                @if($item->tag == 'dispatched')
                    <h6><span class="badge badge-pill badge-primary">{{ __('Dispatched') }}</span></h6>
                @endif
            </div>
        </td>
        <td>
            @php
                $date_entered = null;
                if($item->lifecycle_status == 'Marketplace' && $item->entered_marketplace_date != null){
                    $date_entered = $item->entered_marketplace_date;
                }
                if($item->lifecycle_status == 'Clearance' && $item->entered_clearance_date != null){
                    $date_entered = $item->entered_clearance_date;
                }
            @endphp
            {{ ($date_entered != null)?$date_entered:"" }}
        </td>
        <td width="10%">
            {{ ($item->sold_date != null)?$item->sold_date:"" }}
        </td>
        <td>
            ${{ (isset($item->total) && $item->total != null)?number_format($item->total,2,'.',','):0.00 }}
        </td>
        <td>
            @php
                $buyer = Customer::find($item->buyer_id);
            @endphp
            @if($item->buyer_id > 0 && $buyer != null)
                <div class="">
                    @can('view customers')
                        <a href="{{ route('customer.customers.show', $buyer) }}" target="_blank">{{ $buyer->fullname }}</a>
                    @else
                        {{ $buyer->fullname }}
                    @endcan
                </div>
            @else
                {{ "_" }}
            @endif
        </td>
        <td>
            @can('edit items')
                <a href="{{ route('item.items.edit', $item) }}" class="btn btn-xs btn-outline-info mb-2">{{ __('Edit') }}</a>

                <!-- <button type="button" class="btn btn-xs btn-outline-info mb-2" id="btnHighlight" data-id="{{$item->id}}" data-name="{{$item->name}}" >{{ __('Highlight') }}</button> -->
            @endcan
        </td>
    </tr>
@endforeach

@if(count($items) > 0 && $items->hasPages())
    <tr>
        <td colspan="11" align="center">
            {!! $items->links() !!}
        </td>
    </tr>
@endif