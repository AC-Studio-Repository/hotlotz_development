@php
    use \App\Modules\Item\Models\Item;
@endphp

<table class="table table-striped" width="100%" id="sell_items_table">
    <tr>
        <th>
            {{ Form::checkbox('sell_item_all', 'Y', false, [
                    'id' => "sell_item_all",
                ])
            }}
        </th>
        <th width="10%">Photo</th>
        <th width="10%">Item Number</th>
        <th width="10%">Item Name</th>
        <th>Estimate</th>
        <th>Reserve</th>
        <th>Status</th>
        <th>Hammer Price/Result Price</th>
        <th>Action</th>
    </tr>
    @foreach($sell_items as $key => $item)
        <tr>
            <td>
                {{ Form::checkbox('item_id[]', $item->id, false, [
                        'class' => "sell_item_id",
                    ])
                }}
            </td>
            <td>
                @php
                    $photo = \App\Modules\Item\Models\ItemImage::where('item_id',$item->id)->first();
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
                ${{ number_format($item->low_estimate) }}/${{ number_format($item->high_estimate) }}
            </td>
            <td>
                ${{ ($item->is_reserve == 'Y' && $item->reserve != null)?number_format($item->reserve):0 }}
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
                @can('edit items')
                    <a href="{{ route('item.items.edit_item', [$item, 'cataloguing']) }}" class="btn btn-xs btn-outline-primary mb-2" target="_blank">{{ __('Edit') }}</a>
                    <br>
                @endcan

                @can('create items')
                    <button type="button" class="btn btn-xs btn-outline-warning mb-2" id="btnItemDuplicateConfirm" data-id="{{ $item->id }}" data-name="{{ $item->name }}">{{ __('Duplicate') }}</button>
                    <br>
                @endcan

                @can('delete items')
                    @if( $item->status == Item::_PENDING_ && $item->lifecycle_status == null )
                        <button type="button" class="btn btn-xs btn-outline-danger" id="btnItemDeleteConfirm" data-id="{{ $item->id }}" data-name="{{ $item->name }}" >{{ __('Delete Item') }}</button>
                    @endif
                @endcan
            </td>
        </tr>
    @endforeach
</table>

@if(count($sell_items)>0)
    <hr>
    <nav id="sell_item">
        {!! $sell_items->links() !!}
    </nav>
@endif