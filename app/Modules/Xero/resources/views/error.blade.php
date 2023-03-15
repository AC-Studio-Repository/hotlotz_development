@extends('appshell::layouts.default')

@section('title')
    {{ __('Xero Error List') }}
@stop

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap4.min.css">

@stop

@section('content')

          <div class="card card-accent-secondary">

            <div class="card-header">
                @yield('title')


            </div>

            <div class="card-block">
                <div class="table-responsive">
                   <table class="table table-striped" id="data-table">
                          <thead>
                              <tr>
                                <th>Seller</th>
                                <th>Buyer</th>
                                <th>Item</th>
                                <th>Amount</th>
                                <th>Invoice ID</th>
                                <th>Type</th>
                                <th>Created At</th>
                                <th width="5%">Action</th>
                              </tr>
                          </thead>

                      </table>
                </div>
            </div>

        </div>
@stop

@section('scripts')
<style>
    .pagination, .dataTables_filter{
        float:right !important;
    }
</style>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function() {
    $('#data-table').DataTable({
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[ 6, "desc" ]],
        processing: true,
        serverSide: true,
        ajax: '{!! route('xero.error.datatables') !!}',
        columns: [
            { data: 'seller', name: 'seller'},
            { data: 'buyer', name: 'buyer'},
            { data: 'items', name: 'items'},
            { data: 'amount', name: 'amount'},
            { data: 'invoice_id', name: 'invoice_id'},
            { data: 'type', name: 'type' },
            { data: 'created_at', name: 'created_at'},
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@stop