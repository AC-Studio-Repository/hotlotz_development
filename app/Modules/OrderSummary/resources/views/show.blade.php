@extends('appshell::layouts.default')

@section('title')
    {{ __('Order Detail') }}
@stop

@section('content')
    <div class="card card-accent-secondary">
        <div class="card-header">
            @yield('title') {{ $order->reference_no }}
            <div class="card-actionbar">
                @if($order->status == 'paid' || $order->status == 'pending' )
                <a href="{{ route('customer.customers.show_tab',  ['id' =>  $order->customer->id, 'tab_name' => 'adhoc_invoice', 'order_id' => $order->id]) }}" class="btn btn-outline-primary">{{ __('Adhoc Invoice') }}</a>
                @endif
                @if($order->status !== 'complete')
                 <a href="{{ route('order_summary.order_summaries.edit',  [$order, 'status' => 'complete']) }}" class="btn btn-outline-success">{{ __('Complete') }}</a>
                @endif
                <a href="{{ route('order_summary.order_summaries.edit',  [$order, 'status' => 'cancel']) }}" class="btn btn-outline-danger">{{ __('Cancel') }}</a>
            </div>
        </div>
         <div class="card-block">

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#overview" class="nav-link active" data-toggle="tab">Overview</a>
                </li>
                <li class="nav-item">
                    <a href="#item" class="nav-link {{ $order->items->count() == 0 ? 'disabled' : '' }}" data-toggle="tab">Item(s)</a>
                </li>
                <li class="nav-item">
                    <a href="#address" class="nav-link {{ $order->address_id == null ? 'disabled' : '' }}" data-toggle="tab">Address</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="overview">
                    <div class="row">
                        <div class="col">
                            <strong>Customer: </strong>
                            @can('view customers')
                                <a href="{{ route('customer.customers.show', $order->customer) }}" target="_blank">{{ $order->customer->fullname }} ({{ $order->customer->ref_no }})</a>
                            @else
                             {{ $order->customer->fullname }} ({{ $order->customer->ref_no }})
                            @endcan
                        </div>
                        <div class="col">
                            <strong>Total Item(s): </strong>{{ $order->items->count() }}
                        </div>
                         <div class="col">
                            <strong>Total: </strong>{{ $order->getOrderTotalWithUrl($order) }}
                        </div>
                        <div class="col">
                            <strong>Status: </strong>
                            @if($order->status == 'paid')
                            <span class="badge badge-pill badge-success">Paid</span>
                            @elseif($order->status == 'cancel')
                            <span class="badge badge-pill badge-danger">Cancel</span>
                            @elseif($order->status == 'pending')
                            <span class="badge badge-pill badge-warning">Pending</span>
                            @else
                            <span class="badge badge-pill badge-info">Complete</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col">
                            <strong>Shiping Type: </strong>{{ $order->type }}
                        </div>
                        <div class="col">
                            <strong>Order From: </strong>
                             @if($order->from == 'auction')
                            {{ $order->getOrderFrom($order->invoice_id)}}
                            @else

                            {{ $order->from }}
                            @endif
                        </div>
                        <div class="col"></div>
                        <div class="col"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="item">
                    @php $items = $order->items; @endphp
                    @include('order_summary::show.items')
                </div>
                <div class="tab-pane fade" id="address">
                   @if($order->address)
                     <div class="card card-body">
                        <div>
                            Nick name : {{ $order->address->address_nickname }} <br>
                        </div>
                        <div>
                            First name : {{ $order->address->firstname ?? '-'  }} <br> Last name
                            :{{ $order->address->lastname ?? '-' }}<br>
                        </div>
                        <div>
                            Address 1 : {{ $order->address->address ?? '-' }}<br>
                        </div>
                        <div>
                            Address 2 : {{ $order->address->address2 ?? '-' }}<br>
                        </div>
                        <div>
                            City : {{ $order->address->city ?? '-' }}, Postal Code : {{ $order->address->postalcode ?? '-' }} , Zip
                            Code : {{ $order->address->zip_code ?? '-' }} , State : {{ $order->address->state ?? '-' }} , Country :
                            {{ $order->address->country->name ?? '-' }}<br>
                        </div>
                        <div>
                            Phone : {{ $order->address->daytime_phone ?? '-' }}
                        </div>
                        <div>
                            Delivery Instruction : {{ $order->address->delivery_instruction ?? '-' }}
                        </div>
                      </div>
                    @endif
                </div>
            </div>
        </div>
         <div class="card-footer text-muted">
           {{ $order->created_at->diffForHumans() }}
        </div>
    </div>

@stop

@section('scripts')

@stop