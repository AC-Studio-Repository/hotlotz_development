@php
    use \App\Modules\Item\Models\Item;
@endphp

@foreach($customers as $customer)
    <tr>
        <td>
            <img onclick="imagepreview(this)" lazyload="on" src="{{ $customer->image_path ?? asset('images/appshell/user.png') }}" class="img-avatar-100">
        </td>
        <td>
            <div class="mb-3 font-weight-bold">
                @php
                    $fullname = $customer->client_fullname;
                @endphp

                {{ $fullname }} <br>
            </div>
            <div class="mb-3 font-weight-bold">
                @can('view customers')
                    <a href="{{ route('customer.customers.show', $customer) }}" target="_blank">{{ ($customer->ref_no)?$customer->ref_no:'' }}</a>
                @else
                    {{ ($customer->ref_no)?$customer->ref_no:'' }}
                @endcan
            </div>
        </td>
        <td>
            <div class="mb-3">
                <a href="mailto: {{ $customer->email }}" target="_blank">{{ $customer->email }}</a>
            </div>
            <div class="mb-3">
                {{ $customer->dialling_code }} {{ $customer->phone }}
            </div>
            <div>
                {{ isset($customer->country->name)?$customer->country->name:'' }}
            </div>
        </td>
        <td>
            <span class="mb-3">
                {{ (isset($customer->mainclientcontact) && $customer->mainclientcontact->name != null)? $customer->mainclientcontact->name: 'N/A' }}
            </span>
        </td>
        <td>
            <div class="mb-3">
                <button type="button" class="btn btn-link" data-id="{{$customer->id}}" data-customer_tab="buyer_details" id="btnBought">{{ __('Bought:') }}</button> {{ count($customer->purchaseditems) }}
            </div>
            <div>
                @php
                    $solditems = Item::where('customer_id', $customer->id)->whereIn('status',[Item::_SOLD_,Item::_PAID_,Item::_SETTLED_])->count();
                @endphp
                <button type="button" class="btn btn-link" data-id="{{$customer->id}}" data-customer_tab="seller_details" id="btnBought">{{ __('Sold:') }}</button> {{ $solditems }}
            </div>
        </td>
        <td>
            <div class="mt-2">
                @can('edit customers')
                    <a href="{{ route('customer.customers.edit', $customer) }}" class="btn btn-xs btn-outline-primary btn-show-on-tr-hover mb-2">{{ __('Edit') }}</a>
                    <br>
                @endcan
                @can('create items')
                    <a href="{{ route('item.items.add_item_from_client', $customer->id) }}" class="btn btn-xs btn-outline-primary btn-show-on-tr-hover mb-2">{{ __('Add an item') }}</a>
                    <br>
                @endcan

                @can('delete customers')
                    <button type="button" class="btn btn-xs btn-outline-danger btn-show-on-tr-hover mb-2" id="btnDeleteConfirm" data-id="{{ $customer->id }}" data-ref_no="{{ $customer->ref_no }}" data-fullname="{{ $customer->fullname }}" >{{ __('Delete') }}</button>
                @endcan
                <form action="{{ route('customer.customers.remoteLogin', $customer->id) }}" method="post" target="_blank">
                  @csrf
                  <button type="submit" class="btn btn-xs btn-outline-warning btn-show-on-tr-hover">{{ __('Remote Login') }}</button>

                </form>
            </div>
        </td>
    </tr>

@endforeach

@if(count($customers) > 0 && $customers->hasPages())
    <tr>
        <td colspan="6" align="center">
            {!! $customers->links() !!}
        </td>
    </tr>
@endif