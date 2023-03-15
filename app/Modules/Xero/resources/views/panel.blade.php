@extends('appshell::layouts.default')

@section('title')
    {{ __('Xero Pending Invoice List') }}
@stop

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap4.min.css">

@stop

@section('content')

          <!-- <div class="row mb-3">
              <div class="col-xl-3 col-lg-6">
                  <div class="card card-inverse card-success">
                      <div class="card-block bg-success">
                          <div class="rotate">
                              <i class="fa fa-user fa-5x"></i>
                          </div>
                          <h6 class="text-uppercase">All Invoice</h6>
                          <h1 class="display-1">134</h1>
                      </div>
                  </div>
              </div>
              <div class="col-xl-3 col-lg-6">
                  <div class="card card-inverse card-danger">
                      <div class="card-block bg-danger">
                          <div class="rotate">
                              <i class="fa fa-list fa-4x"></i>
                          </div>
                          <h6 class="text-uppercase">Total Settlement</h6>
                          <h1 class="display-1">87</h1>
                      </div>
                  </div>
              </div>
              <div class="col-xl-3 col-lg-6">
                  <div class="card card-inverse card-info">
                      <div class="card-block bg-info">
                          <div class="rotate">
                              <i class="fa fa-twitter fa-5x"></i>
                          </div>
                          <h6 class="text-uppercase">Total Invoice</h6>
                          <h1 class="display-1">125</h1>
                      </div>
                  </div>
              </div>
              <div class="col-xl-3 col-lg-6">
                  <div class="card card-inverse card-warning">
                      <div class="card-block bg-warning">
                          <div class="rotate">
                              <i class="fa fa-share fa-5x"></i>
                          </div>
                          <h6 class="text-uppercase">Pending Invoice</h6>
                          <h1 class="display-1">36</h1>
                      </div>
                  </div>
              </div>
          </div>

          <hr> -->

          <!-- <div class="row placeholders mb-3">
              <div class="col-6 col-sm-3 placeholder text-center">
                  <img onclick="imagepreview(this)" lazyload="on" src="//placehold.it/200/dddddd/fff?text=1" class="center-block img-fluid rounded-circle"
                      alt="Generic placeholder thumbnail">
                  <h5>Total Auction</h5>
              </div>
              <div class="col-6 col-sm-3 placeholder text-center">
                  <img onclick="imagepreview(this)" lazyload="on" src="//placehold.it/200/e4e4e4/fff?text=2" class="center-block img-fluid rounded-circle"
                      alt="Generic placeholder thumbnail">
                  <h5>Total Item</h5>
              </div>
              <div class="col-6 col-sm-3 placeholder text-center">
                  <img onclick="imagepreview(this)" lazyload="on" src="//placehold.it/200/d6d6d6/fff?text=3" class="center-block img-fluid rounded-circle"
                      alt="Generic placeholder thumbnail">
                  <h5>Total Own Stock</h5>
              </div>
              <div class="col-6 col-sm-3 placeholder text-center">
                  <img onclick="imagepreview(this)" lazyload="on" src="//placehold.it/200/e0e0e0/fff?text=4" class="center-block img-fluid rounded-circle"
                      alt="Generic placeholder thumbnail">
                  <h5>Total Consume Stock</h5>
              </div>
          </div>

          <hr> -->

          <div class="card card-accent-secondary">

            <div class="card-header">
                @yield('title')


            </div>

            <div class="card-block">
                <div class="table-responsive">
                   <table class="table table-striped" id="data-table">
                          <thead>
                              <tr>
                                <th width="17%">Auction</th>
                                <th width="25%">Items</th>
                                <th width="13%">Buyer</th>
                                <th width="13%">Seller</th>
                                <th width="7%">Price</th>
                                <th width="10%">Date</th>
                                <th width="5%">Action</th>
                              </tr>
                          </thead>

                      </table>
                </div>
            </div>

        </div>
        <p class="lead hidden-xs-down">
        @if($xeroCredentials->exists())
        (Already connected with xero)
        @else
        <a href="{{ str_replace('admin.', '', url('xero/auth/authorize')) }}" target='_blank'>
        (Reconneted to xero)
        </a>
        @endif
        </p>
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
        order: [[ 3, "desc" ]],
        processing: true,
        serverSide: true,
        ajax: '{!! route('xero.panel.datatables') !!}',
        columns: [
            { data: 'auction', name: 'auction'},
            { data: 'items', name: 'items'},
            { data: 'buyer', name: 'buyer'},
            { data: 'seller', name: 'seller'},
            { data: 'totalprice', name: 'totalprice'},
            { data: 'created_at_utc', name: 'created_at_utc' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@stop