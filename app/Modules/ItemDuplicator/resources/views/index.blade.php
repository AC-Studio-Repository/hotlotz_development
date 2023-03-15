@extends('appshell::layouts.default')

@section('title')
    {{ __('Item Duplicator') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        {!! Form::model($item, ['route' => 'item_duplicator.item_duplicator.duplicate', 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            <div class="form-row">
                <div class="form-group col-12 col-md-4 col-xl-4">
                    <label class="form-control-label">{{ __('Item') }}</label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12 col-md-4 col-xl-4">
                    {{ Form::select('item_id', [''=>'-- Select Item --'] + $item_lists, null, array('class'=>'form-control', 'required')) }}
                </div>
            </div>
        </div>

        <div class="card-block">
            <div class="form-row">
                <div class="form-group col-12 col-md-4 col-xl-4">
                    <label class="form-control-label">{{ __('Duplication Setting') }}</label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-12 col-md-4 col-xl-4">
                    <label class="form-control-label">{{ __('Total Copy') }}</label>
                    {{ Form::number('total_copy', 10, array('class'=>'form-control') ) }}
                </div>
            </div>
        </div>

{{--        <div class="card-block">--}}
{{--            <div class="form-row">--}}
{{--                <div class="form-group col-12 col-md-4 col-xl-4">--}}
{{--                    <label class="form-control-label">{{ __('Cataloguing') }}</label>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="form-row">--}}
{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('Is Marketplace highlight ?') }}</label>--}}
{{--                    {{ Form::select('is_marketplace_highlight', ['keep' => 'Keep', 'random' => 'Random', 'yes' => 'Yes', 'no' => 'No'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('Has permission to Sell ?') }}</label>--}}
{{--                    {{ Form::select('has_permission_to_sell', ['keep' => 'Keep', 'random' => 'Random', 'yes' => 'Yes', 'no' => 'No'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('Seller') }}</label>--}}
{{--                    {{ Form::select('seller', ['keep' => 'Keep', 'random' => 'Random'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('Brand') }}</label>--}}
{{--                    {{ Form::select('brand', ['keep' => 'Keep', 'random' => 'Random'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('Location') }}</label>--}}
{{--                    {{ Form::select('location', ['keep' => 'Keep', 'random' => 'Random'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('Description') }}</label>--}}
{{--                    {{ Form::select('description', ['keep' => 'Keep', 'random' => 'Random'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="card-block">--}}
{{--            <div class="form-row">--}}
{{--                <div class="form-group col-12 col-md-4 col-xl-4">--}}
{{--                    <label class="form-control-label">{{ __('Valuation & Lifecycle') }}</label>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="form-row">--}}
{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('Valuer') }}</label>--}}
{{--                    {{ Form::select('valuer', ['keep' => 'Keep', 'random' => 'Random'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('GST Rate') }}</label>--}}
{{--                    {{ Form::select('gst_rate', ['keep' => 'Keep', 'random' => 'Random'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('Low Estimate') }}</label>--}}
{{--                    {{ Form::select('low_estimate', ['keep' => 'Keep', 'random' => 'Random'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('High Estimate') }}</label>--}}
{{--                    {{ Form::select('high_estimate', ['keep' => 'Keep', 'random' => 'Random'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('Reserve') }}</label>--}}
{{--                    {{ Form::select('reserve', ['keep' => 'Keep', 'random' => 'Random'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('Is Hotlotz Own Stock') }}</label>--}}
{{--                    {{ Form::select('own_stock', ['keep' => 'Keep', 'random' => 'Random'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--                <div class="form-group col-12 col-md-2 col-xl-2">--}}
{{--                    <label class="form-control-label">{{ __('Life Cycle') }}</label>--}}
{{--                    {{ Form::select('life_cycle', ['keep' => 'Keep', 'random' => 'Random'], 'keep', array('class'=>'form-control')) }}--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Duplicate') }}</button>
        </div>

        {!! Form::close() !!}

    </div>

@stop

@section('scripts')

    <!-- Parsley CSS -->
    <link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
    <!-- Parsley JS -->
    <script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

@stop
