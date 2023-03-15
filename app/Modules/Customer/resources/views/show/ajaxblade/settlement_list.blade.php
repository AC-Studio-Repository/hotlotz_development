<h5><strong>{{ __('Settlement List') }}</strong></h5>
<div class="form-group">
    <table class="table table-striped" width="100%">
        <tr>
            <th>Invoice List</th>
            <th>Settlement Name</th>
            <th>Total</th>
            <th>Payment Status</th>
            <th>Settlement Date</th>
            <th>Bank account of settlement</th>
            <th>Action</th>
        </tr>

        @foreach($settlement_list as $key => $settlement)
            @php
                $settlement_data = $settlement->invoice();
                $auction_date = null;
                $auction_title = null;
                $settlement_number = "Settlement ".$settlement->id;
                $seller_number = null;
                $total = null;
                $status = null;
                $left_to_pay = null;

                if($settlement_data){
                    $settlement_number = ($settlement_data->InvoiceNumber != null)?$settlement_data->InvoiceNumber:$settlement_number;
                    $seller_number = $settlement_data->Reference;
                    $total = $settlement->invoice_amount;
                    $status = $settlement_data->Status;
                    $left_to_pay = $settlement->invoice_amount;
                }

                if($settlement->invoice_type == 'auction' && isset($settlement->auction)){
                    $auction_date = date_format(date_create($settlement->auction->timed_start), 'Y-m-d h:i A');
                    $auction_title = $settlement->auction->title;
                }
            @endphp
            <tr>
                <td>
                    <i class="zmdi zmdi-plus zmdi-hc-lg icon_toggleable" title="Invoice List"  data-toggle="collapse" data-target="#relatedCollapse{{$settlement->id}}" class="accordion-toggle"></i>
                </td>
                <td>
                    <a href="{{ route('xero.genereateInvoiceUrl', $settlement->id) }}" target="_blank">{{ $settlement_number }}</a>
                </td>
                <td>{{ $total }}</td>
                <td>{{ $status }}</td>
                <td>{{ $settlement->invoice_date }}</td>
                <td>{{ $bank_account }}</td>
                <td>
                    @if($settlement->active == 0)
                        <a href="{{ route('xero.publish.bill', [$settlement->id, null]) }}"><button type="button" class="btn btn-sm btn-warning" title="Publish now">
                        <i class="zmdi zmdi-cloud-upload"></i></button></a>
                    @else
                        <button type="button" class="btn btn-sm btn-success" disabled title="Published"><i class="zmdi zmdi-cloud-done"></i>
                        </button>
                        <a href="{{ route('xero.publish.bill', [$settlement->id, null]) }}"><button type="button" class="btn btn-sm btn-warning" title="Resend settlement email">
                        <i class="zmdi zmdi-mail-reply"></i></button></a>
                    @endif
                    <a href="{{ route('xero.syncInvoice', $settlement->invoice_id)  }}"><button type="button" class="btn btn-sm btn-info" title="Sync Invoice"><i class="zmdi zmdi-refresh-alt"></i>
                    </button></a>
                    @if($status != 'PAID')
                    <a href="{{ route('customer.customers.splitSettlement', [$customer->id, $settlement->invoice_id]) }}" target='_blank'><button type="button" class="btn btn-sm btn-danger" title="Split Invoice"><i class="zmdi zmdi-copy"></i>
                    </button></a>
                    @endif
                </td>
            </tr>
            <tr >
                <td colspan="7" class="hiddenRow">
                <div class="accordian-body collapse" id="relatedCollapse{{$settlement->id}}">
                        <div class="row">
                            @foreach($lastRelatedInvoice[$settlement->id] as $key => $relatedInvoice)
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