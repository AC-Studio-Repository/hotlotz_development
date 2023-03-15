@extends('appshell::layouts.default')

@section('title')
    {{ __('Item Lifecycle Trigger') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

        </div>

        {!! Form::model($item, ['route' => 'item_lifecycle_trigger.itemlifecycletriggers.lifecycle', 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

            <div class="card-block">
                <div class="form-row">
                    <div class="form-group col-12 col-md-4 col-xl-4">
                        <label class="form-control-label">{{ __('Item') }}</label>
                        {{ Form::text('item_number', '', ['class'=>'form-control','required', 'placeholder'=> 'A1/1'] ) }}
                    </div>
                    <div class="form-group col-12 col-md-4 col-xl-4">
                        <label class="form-control-label">{{ __('Event Action') }}</label>
                        {{ Form::select('event_action', $event_actions, null, array('class'=>'form-control', 'required')) }}
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-success">{{ __('Lifecycle Event') }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
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
