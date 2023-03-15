@extends('appshell::layouts.default')

@section('title')
    {{ __('Xero Account Service Edit') }}
@stop

@section('styles')
@stop

@section('content')
      <div class="card card-accent-secondary">

        <div class="card-header">
            {{ __('Xero Account Service Edit') }} {{ $xeroItem->item_name }}
        </div>

        <div class="card-block">
        {!! Form::model($xeroItem, ['route' => ['xero.account.services.update', $xeroItem], 'method' => 'PUT']) !!}
            <div class="row">
                <div class="col-md-6">
                    <label class="form-control-label">{{ __('Item Code') }} </label>
                    <div class="form-group">
                       {{ Form::text('item_code', null, [
                                'class' => 'form-control form-control-md' . ($errors->has('item_code') ? ' is-invalid' : ''),
                                'placeholder' => __('Item Code *'),
                                'required',
                                'disabled'
                            ])
                        }}

                        @if ($errors->has('item_code'))
                            <div class="invalid-feedback">{{ $errors->first('item_code') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-control-label">{{ __('Item Name') }}</label>
                    <div class="form-group">
                        {{ Form::text('item_name', null, [
                                'class' => 'form-control form-control-md' . ($errors->has('item_name') ? ' is-invalid' : ''),
                                'placeholder' => __('Item name *'),
                                'required',
                                'disabled'
                            ])
                        }}

                        @if ($errors->has('item_name'))
                            <div class="invalid-feedback">{{ $errors->first('item_name') }}</div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-control-label">{{ __('Purchase Description') }} </label>
                    <div class="form-group">
                        {{ Form::text('purchases_description', null, [
                                'class' => 'form-control form-control-md' . ($errors->has('purchases_description') ? ' is-invalid' : ''),
                                'placeholder' => __('Purchase description *'),
                                'required',
                                'disabled'
                            ])
                        }}

                        @if ($errors->has('purchases_description'))
                            <div class="invalid-feedback">{{ $errors->first('purchases_description') }}</div>
                        @endif
                    </div>
                </div>

                 <div class="col-md-6">
                    <label class="form-control-label">{{ __('Purchase Account') }} </label>
                    <div class="form-group">
                        {{ Form::text('purchases_account', null, [
                                'class' => 'form-control form-control-md' . ($errors->has('purchases_account') ? ' is-invalid' : ''),
                                'placeholder' => __('Purchase account *'),
                                'required',
                                'disabled'
                            ])
                        }}

                        @if ($errors->has('purchases_account'))
                            <div class="invalid-feedback">{{ $errors->first('purchases_account') }}</div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-control-label">{{ __('Sale Description') }} </label>
                    <div class="form-group">
                        {{ Form::text('sales_description', null, [
                                'class' => 'form-control form-control-md' . ($errors->has('sales_description') ? ' is-invalid' : ''),
                                'placeholder' => __('Sale description *'),
                                'required',
                                'autofocus'
                            ])
                        }}

                        @if ($errors->has('sales_description'))
                            <div class="invalid-feedback">{{ $errors->first('sales_description') }}</div>
                        @endif
                    </div>
                </div>

                 <div class="col-md-6">
                    <label class="form-control-label">{{ __('Sale Account') }} </label>
                    <div class="form-group">
                        {{ Form::text('sales_account', null, [
                                'class' => 'form-control form-control-md' . ($errors->has('sales_account') ? ' is-invalid' : ''),
                                'placeholder' => __('Sale account *'),
                                'required',
                                'disabled',
                            ])
                        }}

                        @if ($errors->has('sales_account'))
                            <div class="invalid-feedback">{{ $errors->first('sales_account') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

         <div class="card-footer">
            <button class="btn btn-outline-success">{{ __('Update') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-outline-danger">{{ __('Cancel') }}</a>
        </div>

    </div>
@stop