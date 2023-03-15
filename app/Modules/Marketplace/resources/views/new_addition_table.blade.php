@php
    use \App\Modules\Item\Models\Item;
    use \App\Modules\Item\Models\ItemImage;
    use \App\Modules\Customer\Models\Customer;
    use \App\Modules\Item\Models\ItemLifecycle;
@endphp

<div class="table table-striped table-hover table-responsive" style="overflow-x:auto;">
    <table class="table table-striped" width="100%">
        <thead>
            <tr>
                <th width="1%">
                    {{ Form::checkbox('new_addition_all', 'Y', false, [
                            'id' => "new_addition_all",
                        ])
                    }}
                </th>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Ref. Number') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Seller\'s Name') }}</th>
                <th>{{ __('Buy Now Price') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Date entered') }}</th>
                <th>{{ __('Time left before it moves to clearance') }}</th>
                <th>{{ __('Time left before it moves to storage') }}</th>
                <th>{{ __('Actions') }}</th>
                <th>{{ __('Highlight') }}</th>
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
                        $itemlifecycle = ItemLifecycle::where('item_id',$item->id)
                                ->where('type', strtolower($item->lifecycle_status))
                                ->where('action', ItemLifecycle::_PROCESSING_)
                                ->first();

                        $buy_now_price = 0;
                        if($itemlifecycle != null){
                            $buy_now_price = $itemlifecycle->price;
                        }
                    @endphp
                    ${{ number_format($buy_now_price) }}
                </td>
                <td>
                    <h6><span class="badge badge-pill badge-success">{{ __(ucfirst($item->lifecycle_status)) }}</span></h6>
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
                <td>
                    @php
                        $mp_itemlifecycle = ItemLifecycle::where('item_id',$item->id)->where('type', 'marketplace')->first();

                        $mp_finished_date = null;
                        $left_days = "";
                        if($mp_itemlifecycle != null && $item->lifecycle_status == 'Marketplace' && $mp_itemlifecycle->entered_date != null){
                            $entered_date = strtotime($mp_itemlifecycle->entered_date);
                            $mp_finished_date = strtotime('+'.intval($mp_itemlifecycle->period).' day', $entered_date);
                            $today = time();
                            $datediff = $mp_finished_date - $today;
                            $left_days = round($datediff / (60 * 60 * 24));
                        }
                    @endphp
                    {{ $left_days }}
                </td>
                <td>
                    @php
                        $cl_itemlifecycle = ItemLifecycle::where('item_id',$item->id)->where('type', 'clearance')->first();

                        $cl_finished_date = null;
                        $left_days = "";
                        if($cl_itemlifecycle != null && $item->lifecycle_status == 'Clearance' && $cl_itemlifecycle->entered_date != null){
                            $entered_date = strtotime($cl_itemlifecycle->entered_date);
                            $cl_finished_date = strtotime('+'.intval($cl_itemlifecycle->period).' day', $entered_date);
                            $today = time();
                            $datediff = $cl_finished_date - $today;
                            $left_days = round($datediff / (60 * 60 * 24));
                        }
                    @endphp
                    {{ $left_days }}
                </td>
                <td>
                    @can('edit items')
                        <a href="{{ route('item.items.edit', $item) }}" class="btn btn-xs btn-outline-info mb-2">{{ __('Edit') }}</a>

                        @can('access withdraw')
                            <button type="button" class="btn btn-xs btn-outline-info mb-2" id="btnWithdrawn" data-toggle="modal" data-id="{{$item->id}}" data-name="{{$item->name}}" >{{ __('Withdraw') }}</button>
                        @endcan
                    @endcan
                </td>
                <td>
                    <!-- <button type="button" class="btn btn-xs btn-outline-info mb-2" id="btnHighlight" data-id="{{$item->id}}" data-name="{{$item->name}}" >{{ __('Highlight') }}</button> -->
                    <div style="overflow: hidden; white-space: nowrap;">
                        <label class="switch switch-icon switch-pill switch-primary">
                            <input class="switch-input" data-id="{{ $item->id }}" data-name="{{$item->name}}" type="checkbox" {{ ($item->is_highlight == 'Y') ? 'checked' : ''}}>

                            <span class="switch-label" data-on="&#xf26b;" data-off="&#xf136;"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
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