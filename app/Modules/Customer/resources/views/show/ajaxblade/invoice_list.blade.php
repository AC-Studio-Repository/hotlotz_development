<h5><strong>{{ __('Invoice History') }}</strong></h5>
<div class="table-responsive">
    <table class="table table-striped" width="100%">
        <tr>
            <th>#</th>
            <th>Invoice Number</th>
            <th>Invoice Reference</th>
            <th>Auction Title</th>
            <th>Sale Date</th>
            <th>Invoice Total</th>
            <th>Method of payment</th>
            <th width="10%">Action</th>
        </tr>
        @php
            $payment_methods = $customer->credit_cards;
        @endphp
        @foreach($invoice_list as $key => $invoice)
            @php
                $invoice_data = $invoice->invoice();
                $auction_date = null;
                $auction_title = null;
                $invoice_number = "Invoice ".$invoice->id;
                $buyer_number = null;
                $total = null;
                $left_to_pay = null;

                if($invoice_data){
                    $invoice_number = $invoice_data->InvoiceNumber;
                    $buyer_number = $invoice_data->Reference;
                    $total = $invoice->invoice_amount;
                    $left_to_pay = $invoice->invoice_amount;
                }

                if($invoice->invoice_type == 'auction'){
                    if (isset($invoice->auction)) {
                        $auction_date = date_format(date_create($invoice->auction->timed_first_lot_ends), 'Y-m-d h:i A');
                        $auction_title = $invoice->auction->title;
                    }
                }
            @endphp
            <tr>
                 <td>
                    <i class="zmdi zmdi-plus zmdi-hc-lg icon_toggleable" title="Realted Settlement"  data-toggle="collapse" data-target="#relatedCollapse{{$invoice->id}}" class="accordion-toggle"></i>
                </td>
                <td>
                    <a href="{{ ($invoice->url($invoice->id) != null) ? $invoice->url($invoice->id) : '#' }}" target="_blank">{{ $invoice_number }}</a>
                </td>
                <td>{{ $buyer_number }}</td>
                <td>
                    @if($auction_title != null && isset($invoice->auction))
                        <a href="{{ route('auction.auctions.show', $invoice->auction) }}" target="_blank">{{ $auction_title }}</a>
                    @endif
                </td>
                <td>{{ $invoice->invoice_date }}</td>
                <td>{{ $total }}</td>
                <td>

                    @if($invoice->status == 'Awaiting Payment' && $invoice->payment_processing == 0)
                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#chargesModal{{ $invoice->id }}">Charge now</button>
                    @elseif($invoice->status == 'Awaiting Payment' && $invoice->payment_processing == 1)
                        <button type="button" class="btn btn-sm btn-danger" onclick="declineInvoice('{{$invoice->id}}')"> Decline Invoice</button>
                    @else
                        <button type="button" class="btn btn-sm btn-success" disabled>{{ $invoice->status }}</button>
                    @endif

                </td>
                <td>
                    @if($invoice->active == 0)
                        <a href="{{ route('xero.publish.invoice', [$invoice->id, null]) }}"><button type="button" class="btn btn-sm btn-info" title="Publish now"><i class="zmdi zmdi-cloud-upload"></i></button></a>
                    @else
                        <button type="button" class="btn btn-sm btn-success" disabled title="Published"><i class="zmdi zmdi-cloud-done"></i></button>
                    @endif
                    <a href="{{ route('xero.syncInvoice', $invoice->invoice_id)  }}"><button type="button" class="btn btn-sm btn-info"title="Sync Invoice"><i class="zmdi zmdi-refresh-alt"></i></button></a>
                </td>
                @include('stripe::payments.modal')
            </tr>
        <tr >
            <td colspan="8" class="hiddenRow">
            <div class="accordian-body collapse" id="relatedCollapse{{$invoice->id}}">
                <div class="row">
                @foreach($lastRelatedBill[$invoice->id] as $key => $relatedInvoice)
                <div class="col-4">
                    <div class="card border-dark mt-3 mb-3">
                    <div class="card-header"><a href="{{ route('xero.genereateInvoiceUrl', $relatedInvoice['id']) }}" target="_blank">
                    {{ $relatedInvoice['ref'] }}
                    </a></div>
                    <div class="card-body text-dark">
                        <h5 class="card-title">Including Item(s) - {{ count($relatedInvoice['items']) }}</h5>
                        <ul class="list-group">
                        @foreach($relatedInvoice['items'] as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p>
                                <a href="{{ route('item.items.show_item', [$item,'cataloguing']) }}" target="_blank">{{ $item->name }}</a>
                                <a href="{{ route('item.items.show_item', [$item,'overview']) }}" target="_blank">({{ $item->item_number }})</a>
                            </p>
                            <span class="badge badge-success badge-pill">{{ $item->status }}</span>
                        </li>
                        @endforeach
                        </ul>
                    </div>
                    </div>
                </div>
                @endforeach

                </div>
            </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>