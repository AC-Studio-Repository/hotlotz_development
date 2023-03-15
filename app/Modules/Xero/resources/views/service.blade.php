@extends('appshell::layouts.default')

@section('title')
    {{ __('Xero Account Service') }}
@stop

@section('styles')
@stop

@section('content')
      <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <!-- <a href="{{ route('xero.account.services.sync.all') }}"><button class="btn btn-sm btn-outline-success" style="float:right;"><i class="zmdi zmdi-refresh zmdi-hc-fw"></i>All Sync</button></a> -->
        </div>

        <div class="card-block">
            <div class="table-responsive">
                <table class="table table-striped" id="data-table">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Purchases Description</th>
                            <th>Purchases Account</th>
                            <th>Sale Description</th>
                            <th>Sale Account</th>
                            @can('edit account_service')
                            <th width="10px;">Action</th>
                            @endcan
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
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
        order: [[ 0, "asc" ]],
        processing: true,
        serverSide: true,
        ajax: '{!! route('xero.account.services.datatables') !!}',
        columns: [
            { data: 'item_code', name: 'item_code'},
            { data: 'item_name', name: 'item_name' },
            { data: 'purchases_description', name: 'purchases_description'},
            { data: 'purchases_account', name: 'purchases_account' },
            { data: 'sales_description', name: 'sales_description' },
            { data: 'sales_account', name: 'sales_account' },
            @can('edit account_service')
            { data: 'action', name: 'action', orderable: false, searchable: false }
            @endcan
        ]
    });
});
</script>
@stop