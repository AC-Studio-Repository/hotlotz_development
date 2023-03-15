@extends('appshell::layouts.default')

@section('title')
    {{ __('Auction Details') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('No Permission Items') }}
    </div>

    <div class="card-block">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('Item Number') }}</th>
                            <th>{{ __('Item Name') }}</th>
                            <th>{{ __('Seller Name') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($no_permission_items as $lot)
                        <tr>
                            <td>
                                <div class="text-muted">
                                    @php
                                        $item = \App\Modules\Item\Models\Item::find($lot->item_id);
                                    @endphp
                                    @if($item)
                                        <a href="{{ route('item.items.show', $item) }}" target="_blank">{{ $lot->item_number }}</a>
                                    @else
                                        <a href="#">{{ $lot->item_number }}</a>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-muted">
                                    {{ $lot->name }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $customer = \App\Modules\Customer\Models\Customer::find($lot->customer_id);
                                @endphp
                                @if($customer)
                                    <a href="{{ route('customer.customers.show', $customer) }}" target="_blank">{{ $customer->fullname }}</a>
                                @else
                                    <a href="#">{{ $customer->fullname }}</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@stop