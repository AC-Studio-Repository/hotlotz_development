@php
    use \App\Modules\Item\Models\Item;
    use \App\Modules\Item\Models\ItemImage;
    use \App\Modules\Item\Models\ItemLifecycle;
@endphp

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">Number of Items : {{ $items_count }}</label>
    </div>
</div>
<div class="table table-striped table-hover table-responsive" style="overflow-x:auto;">
    <table class="table table-striped" width="100%">
        <thead>
            <tr>
                <th width="1%">{{ __('#') }}</th>
                <th width="10%">{{ __('Preview') }}</th>
                <th>{{ __('Item Detail') }}</th>
                <th width="30%">{{ __('Pricing') }}</th>
                <th width="10%">{{ __('Status') }}</th>
                <th width="10%">{{ __('Auction/Marketplace') }}</th>
                <th width="20%">{{ __('Purchase Details') }}</th>
                <th width="10%">Actions</th>
            </tr>
        </thead>

        <tbody>
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
                            <img onclick="imagepreview(this)" lazyload="on" src="{{ $photo->image_path }}" alt="{{$photo->file_name}}" width="150px" height="auto" full="{{$photo->full_path}}">
                        @endif
                    </div>
                </td>
                <td>
                    @can('view items')
                        <div class="mb-4 font-weight-bold">
                            <a href="{{ route('item.items.show_item', [$item,'cataloguing']) }}" target="_blank">{{ $item->name }}</a>
                        </div>

                        <div class="mb-1 font-weight-bold">
                            <a href="{{ route('item.items.show_item', [$item,'overview']) }}" target="_blank">{{ $item->item_number }}</a>
                        </div>
                    @else
                        <div class="mb-4">
                            {{ __($item->name) }}
                        </div>
                        <div class="mb-1">
                            {{ __($item->item_number) }}
                        </div>
                    @endcan

                    <div class="mb-1">
                        {{ (isset($item->category) && isset($item->category_id))?$item->category->name:null }}
                    </div>

                    <div class="mb-1 font-weight-bold">
                        @can('view customers')
                            @if(isset($item->customer))
                                <!-- <a href="{{ route('customer.customers.show', $item->customer) }}" target="_blank">{{ $item->customer->fullname }}</a> -->
                                <a href="{{ route('customer.customers.show', $item->customer) }}" target="_blank">{{ $item->customer->select2_fullname }}</a>
                            @endif
                        @else
                            {{ isset($item->customer) ? $item->customer->fullname : '_' }}
                        @endcan
                    </div>
                </td>
                <td class="font-sm">
                    <div class="row">
                        <div class="col-6">
                            {{ __('Estimate') }} <br>
                            ${{ number_format($item->low_estimate) }} - ${{ number_format($item->high_estimate) }}
                        </div>
                        <div class="col-6">
                            {{ __('Reserve') }} <br>
                            ${{ ($item->is_reserve == 'Y' && $item->reserve != null)?number_format($item->reserve):0.00 }}
                        </div>
                        <div class="col-6 mt-4">
                            {{ __('Opening Bid') }} <br>
                            ${{ (isset($item->itemlifecycles[0]) && $item->itemlifecycles[0]->type === 'auction')?number_format($item->itemlifecycles[0]->price):0.00 }}
                        </div>
                        <div class="col-6 mt-4">
                            {{ __("Seller's Commission") }} <br>

                            @if(isset($item->fee_structure) && $item->fee_type === 'sales_commission')
                                {{ str_replace( array("$", "%", "+"), '', $item->fee_structure->sales_commission) }}%
                            @endif

                            @if(isset($item->fee_structure) && $item->fee_type === 'fixed_cost_sales_fee')
                                ${{ str_replace( array("$", "%", "+"), '', $item->fee_structure->fixed_cost_sales_fee) }}
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <div class="mb-4">
                        @if($item->status == Item::_SOLD_)
                            <span class="badge badge-pill badge-warning">{{ __($item->status) }}</span>
                        @elseif($item->status == Item::_PAID_)
                            <span class="badge badge-pill badge-info">{{ __($item->status) }}</span>
                        @elseif($item->status == Item::_SETTLED_)
                            <span class="badge badge-pill badge-secondary">{{ __($item->status) }}</span>
                        @else
                            <span class="badge badge-pill badge-success">{{ __($item->status) }}</span>
                        @endif

                        @if( in_array($item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_, Item::_WITHDRAWN_, Item::_STORAGE_, Item::_UNSOLD_, Item::_ITEM_RETURNED_]) && $item->tag == 'in_storage' )
                            <br/>
                            <span class="badge badge-pill badge-danger">{{ __('In Storage') }}</span>
                        @endif
                        @if($item->tag == 'dispatched')
                            <br/>
                            <span class="badge badge-pill badge-primary">{{ __('Dispatched') }}</span>
                        @endif
                    </div>
                </td>
                <td>                    
                    @php
                        $info = 'Not Allocated';
                        if($item->status == Item::_IN_MARKETPLACE_){
                            $info = 'Marketplace';
                        }

                        if(in_array($item->status, [Item::_PENDING_IN_AUCTION_, Item::_IN_AUCTION_])){
                            $itemlifecycle = ItemLifecycle::where('item_id',$item->id)->where('type','auction')->where('action', Item::_PROCESSING_)->first();
                            $info = ($itemlifecycle && $itemlifecycle->auction)?$itemlifecycle->auction->title:'N/A';
                        }
                    @endphp
                    {{ __($info) }}
                </td>
                <td>
                    @if($item->buyer_id > 0)
                        <div class="mb-1">
                            {{ $item->lifecycle_status }}
                        </div>
                        <div class="mb-1">
                            ${{ ($item->sold_price != null)?number_format($item->sold_price):0.00 }}
                        </div>
                        <div class="font-weight-bold">
                            @can('view customers')
                                @if(isset($item->buyer))
                                    <a href="{{ route('customer.customers.show', $item->buyer) }}" target="_blank">{{ $item->buyer->fullname }}</a>
                                @endif
                            @else
                                {{ isset($item->buyer) && $item->buyer->fullname != null ? $item->buyer->fullname : 'N/A' }}
                            @endcan
                        </div>
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @can('edit items')
                        <a href="{{ route('item.items.edit', $item) }}" class="btn btn-xs btn-outline-primary mb-2 btn-show-on-tr-hover">{{ __('Edit') }}</a>
                        <br>
                    @endcan
                    @can('create items')
                        <button type="button" class="btn btn-xs btn-outline-warning mb-2 btn-show-on-tr-hover" id="btnDuplicateConfirm" data-id="{{ $item->id }}" data-name="{{ $item->name }}">{{ __('Duplicate') }}</button>
                        <br>
                    @endcan
                    @can('delete items')
                        @if( in_array($item->status, [Item::_SWU_, Item::_PENDING_]) && $item->lifecycle_status == null )
                            <button type="button" class="btn btn-xs btn-outline-danger btn-show-on-tr-hover" id="btnDeleteConfirm" data-id="{{ $item->id }}" data-name="{{ $item->name }}" >{{ __('Delete') }}</button>
                            <br>
                        @endif
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@if($items->hasPages())
    <hr>
    <nav>
        {!! $items->links() !!}
    </nav>
@endif
