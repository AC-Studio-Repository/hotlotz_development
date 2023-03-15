@extends('appshell::layouts.default')

@section('title')
    {{ __('Sync Xero Invoice') }}
@stop

@section('styles')
@stop

@section('content')
      <div class="card card-accent-secondary">

        <div class="card-header">
            {{ __('Sync Xero Invoice') }}
        </div>

        <div class="card-block">
                @if(sizeof($invoices) > 0)
                    <form action="" method="post" id="form">
                        @csrf
                        <input type="hidden" name="invoice_ids" value="{{ json_encode($invoices) }}">
                        <button type="submit" id="btnSubmit" class="btn btn-outline-success"> <i class="zmdi zmdi-refresh-sync zmdi-hc-fw"></i>Sync Now <b>({{ sizeof($invoices) }} invoice(s) pending)</b></button>
                    </form>
                @else
                    Up to Date.
                @endif
        </div>

         <div class="card-footer">
            <a href="#" onclick="history.back();" class="btn btn-outline-danger">{{ __('Exit') }}</a>
        </div>

    </div>
@stop

@section('scripts')

<script>
    $('#form').submit(function () {
        $("#btnSubmit").attr("disabled", true);

        $("#btnSubmit").text("Processing...");

        $this.submit();
    });
</script>

@stop
