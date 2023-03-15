@extends('appshell::layouts.default')

@section('title')
    {{ __('Sell With Us Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'layers',
                    'type' => 'success'
            ])
                @slot('subtitle')
                    {{ __('Question') }}
                    {{ $sell_with_us->question }}
                @endslot
                @slot('subtitle')
                    {{ __('Answer') }}
                    {{ $sell_with_us->answer }}
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
                    {{ $sell_with_us->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('sell_with_us.sell_with_uss.edit', $sell_with_us) }}" class="btn btn-outline-primary">{{ __('Edit Sell With Us')
            }}</a>

            @yield('actions')

            {!! Form::open(['route' => ['sell_with_us.sell_with_uss.destroy', $sell_with_us],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete this?'),
                                        'class' => 'float-right'
                                       ])
            !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Sell With Us') }}
                </button>
            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-6">
                {{ $sell_with_us->question }}
            </label>
            <label class="control-label col-md-6">
                {{ $sell_with_us->answer }}
            </label>
        </div>
    </div>
@stop
