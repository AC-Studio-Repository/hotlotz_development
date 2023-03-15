@php
    use \App\Modules\Item\Models\Item;
    use \App\Modules\Item\Models\ItemImage;
    use \App\Modules\Item\Models\ItemLifecycle;
    use \App\Modules\Auction\Models\Auction;
@endphp

<table class="table table-striped" width="100%">
    <tr>
        <th>
            {{ Form::checkbox('purchase_item_all', 'Y', false, [
                    'id' => "purchased_item_all",
                ])
            }}
        </th>
        <th width="5%">Photo</th>
        <th width="5%">Item Number</th>
        <th>Item Name</th>
        <th>Status</th>
        <th>Hammer Price/Result Price</th>
        <th>Sale Date</th>
        <th>Purchased from Auction / Marketplace </th>
        <th>Invoice Number</th>
    </tr>
    @foreach($purchased_items as $key => $item)
        <tr>
            <td>
                {{ Form::checkbox('item_id[]', $item->id, false, [
                        'class' => "purchased_item_id",
                    ])
                }}
            </td>
            <td>
                @php
                    $photo = ItemImage::where('item_id',$item->id)->first();
                @endphp

                @if(isset($photo))
                    <img onclick="imagepreview(this)" lazyload="on" src="{{ $photo->image_path }}" alt="{{$photo->file_name}}" width="150px" height="auto" full="{{$photo->full_path}}">
                @endif
            </td>
            <td>
                <a href="{{ route('item.items.show_item', [$item,'overview']) }}" target="_blank">{{ $item->item_number }}</a>
            </td>
            <td>
                <a href="{{ route('item.items.show_item', [$item,'cataloguing']) }}" target="_blank">{{ $item->name }}</a>
            </td>
            <td>
                @if($item->status == Item::_SOLD_)
                    <span class="badge badge-pill badge-warning">{{ __($item->status) }}</span>
                @elseif($item->status == Item::_PAID_)
                    <span class="badge badge-pill badge-info">{{ __($item->status) }}</span>
                @elseif($item->status == Item::_SETTLED_)
                    <span class="badge badge-pill badge-secondary">{{ __($item->status) }}</span>
                @else
                    <span class="badge badge-pill badge-success">{{ __($item->status) }}</span>
                @endif
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
            <td>
                @if($item->sale_date != null)
                    {{ date_format(date_create($item->sale_date), 'Y-m-d h:i A') }}
                @endif
            </td>
            <td>
                @if($item->lifecycle_status == Item::_AUCTION_)
                    @php
                        $item_lifecycle = ItemLifecycle::where('item_id',$item->id)->where('type','auction')->whereNotNull('status')->whereIn('status',[Item::_SOLD_, Item::_PAID_, Item::_SETTLED_])->first();
                        $auction = Auction::find($item_lifecycle->reference_id);
                    @endphp

                    {{ isset($auction)? $auction->title : $item->lifecycle_status }}
                @else
                    {{ $item->lifecycle_status }}
                @endif
            </td>
            <td>
                {{ isset($item->invoice)?$item->invoice_id:'' }}
            </td>
        </tr>
    @endforeach
</table>

@if(count($purchased_items)>0)
    <hr>
    <nav id="purchased_item">
        {!! $purchased_items->links() !!}
    </nav>
@endif