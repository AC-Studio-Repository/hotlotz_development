@extends('appshell::layouts.default')

@section('title')
    {{ __('Order Summary') }}
@stop

@section('content')
    <div class="card card-accent-secondary">

        <div class="card-header">
            <nav>
              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link {{ app('request')->input('status') !== 'complete' ? 'active' : '' }}" id="nav-home-tab" href="{{url()->current()}}" role="tab" aria-controls="nav-home" aria-selected="true">All</a>
                <a class="nav-item nav-link {{ app('request')->input('status') == 'complete' ? 'active' : '' }}" id="nav-profile-tab" href="?status=complete" role="tab" aria-controls="nav-profile" aria-selected="false">Complete</a>
              </div>
            </nav>
        </div>

         <div class="card-block">
            @include('order_summary::_filter')
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Order Reference') }} </th>
                            <th>{{ __('Customer') }}</th>
                            <!-- <th width="3%">{{ __('Total Item') }}</th> -->
                            <th>{{ __('From') }}</th>
                            <th width="8%">{{ __('Type') }}</th>
                            <th width="8%">{{ __('Total') }}</th>
                            <th width="8%">{{ __('Status') }}</th>
                            <th width="3%">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @include('order_summary::_pagination')
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@stop

@section('scripts')
<!-- Select2 CSS -->
<link href="{{asset('plugins\select2-develop\dist\css\select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins\select2-bootstrap4-theme-master\dist\select2-bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Select2 JS -->
<script src="{{asset('plugins\select2-develop\dist\js\select2.full.min.js')}}"></script>
<!-- ### Additional CSS ### -->
<link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" />
<script src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>

<script>

    $('.orderFrom').select2({
      placeholder: "Search..",
      allowClear: true,
      ajax: {
        url: "order-summaries-froms",
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (result, i) {
                  return {
                      text: result,
                      id: i
                  }
              })
          };
        },
        cache: true
      }
    })

    $( "#autocomplete" ).autocomplete({
        source: function( request, response ) {
        $.ajax({
            url: "order-summaries-customers",
            dataType: "json",
            success: function( data ) {
            response( data );
            }
        });
        }
    });

    function filter() {
         $('#filter').submit();
    }

     $('#autocomplete').change(function(event){
        var keyCode = (event.keyCode ? event.keyCode : event.which);
        if (keyCode == 13) {
            filter();
            return false;
        }
    });

    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            filter();
            return false;
        }
    });
</script>
@stop
