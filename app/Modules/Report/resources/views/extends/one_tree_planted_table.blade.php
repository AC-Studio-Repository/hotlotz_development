@php
    use \App\Modules\Item\Models\Item;
    use \App\Modules\Item\Models\ItemImage;
    use \App\Modules\Customer\Models\Customer;
    use \App\Modules\Item\Models\ItemLifecycle;
@endphp

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">Number of Items : {{ $items_count }}</label>
    </div>
</div>
<div class="table table-striped table-hover table-responsive" style="overflow-x:auto;">
    <table class="table table-striped" width="100%" id="one_tree_planted">
        <thead>
            <tr>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Ref. Number') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Seller') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Sold Price') }}</th>
                <th>{{ __('Sold Date') }}</th>
                <th>{{ __('Buyer') }}</th>
                <th>{{ __('Status') }}</th>
            </tr>
        </thead>

        <tbody>
        @foreach($items as $item)
            <tr>
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
                    {{ isset($item->customer)?$item->customer->name:'' }}
                </td>
                <td>
                    {{ isset($item->category)?$item->category->name:'' }}
                </td>
                <td>
                    {{ $item->sold_price }}
                </td>
                <td>
                    {{ isset($item->sold_date)?date_format(date_create($item->sold_date), 'Y-m-d h:i A'):'' }}
                </td>
                <td>
                    {{ isset($item->buyer)?$item->buyer->name:'' }}
                </td>
                <td>
                    {{ $item->status }}
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