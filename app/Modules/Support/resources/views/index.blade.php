@extends('appshell::layouts.default')

@section('title')
    {{ __('Support') }}
@stop

@section('content')

    @can('access customer email verify')
    <div class="card card-accent-secondary">

        <div class="card-header">Verify Customer's Email</div>

        {!! Form::open(['route' => ['support.verify_customer_email'], 'method' => 'POST']) !!}

        <div class="card-block">
            <div class="form-row">
                <div class="form-group col-12 col-md-4 col-xl-4">
                    <label class="form-control-label">{{ __('Customer\'s Reference No.') }}</label>
                    {{ Form::text('ref_no', '', ['class'=>'form-control','required', 'placeholder'=> 'A101'] ) }}
                </div>
            </div>
        </div>

        @can('modify customer email verify')
        <div class="card-footer">
            <button class="btn btn-success">{{ __('Verify Email') }}</button>
        </div>
        @endcan

        {!! Form::close() !!}
    </div>
    @endcan

@stop
