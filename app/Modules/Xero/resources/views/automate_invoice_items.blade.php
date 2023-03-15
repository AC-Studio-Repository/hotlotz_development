@extends('appshell::layouts.default')

@section('title')
    {{ __('Automate Xero Invoice Items') }}
@stop

@section('styles')
@stop

@section('content')
<div class="card-header">Automate Xero Invoice Items</div>

{!! Form::open(['route' => ['xero.check_invoice_items'], 'method' => 'POST']) !!}

<div class="card-block">
    <div class="form-row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Check by Auction ID') }}</label>
            {{ Form::text('auction_id', '', ['class'=>'form-control','required', 'placeholder'=> 'UUID'] ) }}
        </div>
    </div>
</div>

<div class="card-footer">
    <button class="btn btn-success">{{ __('Check') }}</button>
</div>

{!! Form::close() !!}
@stop

@section('scripts')

@stop
