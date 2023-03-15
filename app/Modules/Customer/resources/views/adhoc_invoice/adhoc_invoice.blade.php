<div class="card-block">
    <div class="form-row">
        <div class="form-group col-12 col-md-12 col-xl-12 select-box">
            <button type="button" class="btn btn-success" id="addNewInvoiceButton" data-toggle="modal" data-target="#newInvoiceModal"><i class="zmdi zmdi-plus"></i> {{ __('Add New Invoice') }}</button>
        </div>
    </div>

    <div class="form-row">
        <table class="table table-striped" id="invoices_table">
        	<thead>
                <tr>
                    <th>Invoice</th>
                    <th>Total</th>
                    <th>Left To Pay</th>
                    <th>Invoice Date</th>
                </tr>
            </thead>
            <tbody>
                @if(count($adhoc_invoices)>0)
                    @foreach($adhoc_invoices as $invoice)
                        @php
                            $invoice_data = $invoice->invoice();
                            $invoice_number = "Invoice ".$invoice->id;
                            $buyer_number = null;
                            $total = null;
                            $left_to_pay = null;

                            if(isset($invoice_data)){
                                $invoice_number = $invoice_data->InvoiceNumber;
                                $buyer_number = $invoice_data->Reference;
                                $total = $invoice->invoice_amount;
                                $left_to_pay = $invoice->invoice_amount;
                            }
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ ($invoice->url($invoice->id) != null)?$invoice->url($invoice->id):'#' }}" target="_blank">{{ $invoice_number }}</a>
                            </td>
                            <td>{{ $total }}</td>
                            <td>{{ $left_to_pay }}</td>
                            <td>{{ $invoice->invoice_date }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

@section('scripts')
@parent

@include('customer::adhoc_invoice.new_invoice_modal')
@include('customer::adhoc_invoice.new_invoice_template')
@include('customer::adhoc_invoice.invoice_js')

@stop