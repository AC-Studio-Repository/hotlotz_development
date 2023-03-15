@extends('appshell::layouts.default')

@section('title')
    {{ __('Policy Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'layers',
                    'type' => 'success'
            ])
                {{ $policy->menu_name }}
                @slot('subtitle')
                    {{ $policy->title }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'time-countdown',
                    'type' => null
            ])
                
                @slot('subtitle')
                    {{ __('Created since') }}
                    {{ $policy->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('policy.policies.edit', $policy) }}" class="btn btn-outline-primary">{{ __('Edit Policy')
            }}</a>

            @yield('actions')

            {!! Form::open(['route' => ['policy.policies.destroy', $policy],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $policy->menu_name]),
                                        'class' => 'float-right'
                                       ])
            !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Policy') }}
                </button>
            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-3">
                {{ __('Policy Name : ') }}
            </label>
            <label class="control-label col-md-6">
                {{ $policy->menu_name }}
            </label>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-3">
                {{ __('Policy Title Header : ') }}
            </label>
            <label class="control-label col-md-6">
                {{ $policy->title }}
            </label>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-3">
                {{ __('Policy Title Blog : ') }}
            </label>
            <label class="control-label col-md-6">
                {!! $policy->content !!}
            </label>
        </div>
    </div>
@stop
