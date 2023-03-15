

@foreach($orders as $order)
    <tr>
        <td>
            <span class="font-lg mb-3 font-weight-bold">
                <a href="{{ route('order_summary.order_summaries.show', $order->id) }}" target="_blank">{{ ($order->reference_no) ? $order->reference_no : '' }}</a>
            </span>
        </td>
        <td>
            <span class="mb-3">
                @can('view customers')
                    <a href="{{ route('customer.customers.show', $order->customer) }}" target="_blank">{{ $order->customer->fullname }} ({{ $order->customer->ref_no }})</a>
                @else
                 {{ $order->customer->fullname }} ({{ $order->customer->ref_no }})
                @endcan
            </span>
        </td>

         <!-- <td>
            <span class="mb-3">
                {{ $order->items->count() }}
            </span>
        </td> -->
        <td>
            <div class="mb-3">
                @if($order->from == 'auction')
                {{ $order->getOrderFrom($order->invoice_id)}}
                @else

                {{ $order->from }}
                @endif
            </div>

        </td>
        <td>
            <span class="mb-3">
                {{ $order->type }}
            </span>
        </td>
         <td>
            <span class="mb-3">
                {{ $order->getOrderTotalWithUrl($order) }}
            </span>
        </td>
        <td>
            <div class="mt-2">
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
        </td>

        <td>
            <div class="mt-2">
                @if($order->status == 'paid' || $order->status == 'pending' )
                    <a href="{{ route('customer.customers.show_tab',  ['id' =>  $order->customer->id, 'tab_name' => 'adhoc_invoice', 'order_id' => $order->id]) }}" class="btn btn-xs btn-outline-primary mb-2" target="_blank">{{ __('Adhoc Invoice') }}</a>
                @endif
                @if($order->status !== 'complete')
                 <a href="{{ route('order_summary.order_summaries.edit',  [$order, 'status' => 'complete']) }}" class="btn btn-xs btn-outline-success mb-2">{{ __('Complete') }}</a>
                @endif
                <a href="{{ route('order_summary.order_summaries.edit',  [$order, 'status' => 'cancel']) }}" class="btn btn-xs btn-outline-danger mb-2">{{ __('Cancel') }}</a>
            </div>
        </td>
    </tr>

@endforeach

@if(count($orders) > 0 && $orders->hasPages())
    <tr>
        <td colspan="12" align="center">

            {!! $orders->
            appends(
                array("search" => request('search'),
                    "from" => request('from'),
                    "type" => request('orderType'),
                    "status" => request('status')
                )
            )->
            links() !!}
        </td>
    </tr>
@endif