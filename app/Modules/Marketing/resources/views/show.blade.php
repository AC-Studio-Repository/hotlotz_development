@extends('appshell::layouts.default')

@section('title')
    {{ __('Viewing') }} {{ $marketing->getName() }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => enum_icon($marketing->type),
                    'type' => $marketing->is_active ? 'success' : 'warning'
            ])
                {{ $marketing->getName() }}
                @if (!$marketing->is_active)
                    <small>
                        <span class="badge badge-default">
                            {{ __('inactive') }}
                        </span>
                    </small>
                @endif
                @slot('subtitle')
                    {{ $marketing->email }}
                    {{ $marketing->phone }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'time-countdown',
                    'type' => $marketing->last_purchase_at ? 'success' : null
            ])
                {{ __('Last purchase') }}
                <span title="{{ $marketing->last_purchase_at ? $marketing->last_purchase_at : '' }}">{{ $marketing->last_purchase_at ? $marketing->last_purchase_at->diffForHumans() : __('never') }}</span>

                @slot('subtitle')
                    {{ __('Marketing since') }}
                    {{ $marketing->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            @can('edit marketings')
            <a href="{{ route('marketing.marketings.edit', $marketing) }}" class="btn btn-outline-primary">{{ __('Edit marketing')
            }}</a>
            @endcan

            @yield('actions')

            @can('delete marketings')
                {!! Form::open(['route' => ['marketing.marketings.destroy', $marketing],
                                            'method' => 'DELETE',
                                            'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $marketing->getName()]),
                                            'class' => 'float-right'
                                           ])
                !!}
                    <button class="btn btn-outline-danger">
                        {{ __('Delete marketing') }}
                    </button>
                {!! Form::close() !!}
            @endcan

        </div>
    </div>

@stop
