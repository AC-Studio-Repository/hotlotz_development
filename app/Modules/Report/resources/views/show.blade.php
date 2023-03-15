@extends('appshell::layouts.default')

@section('title')
    {{ __('Viewing') }} {{ $report->getName() }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => enum_icon($report->type),
                    'type' => $report->is_active ? 'success' : 'warning'
            ])
                {{ $report->getName() }}
                @if (!$report->is_active)
                    <small>
                        <span class="badge badge-default">
                            {{ __('inactive') }}
                        </span>
                    </small>
                @endif
                @slot('subtitle')
                    {{ $report->email }}
                    {{ $report->phone }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'time-countdown',
                    'type' => $report->last_purchase_at ? 'success' : null
            ])
                {{ __('Last purchase') }}
                <span title="{{ $report->last_purchase_at ? $report->last_purchase_at : '' }}">{{ $report->last_purchase_at ? $report->last_purchase_at->diffForHumans() : __('never') }}</span>

                @slot('subtitle')
                    {{ __('Auction since') }}
                    {{ $report->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            @can('edit reports')
            <a href="{{ route('admin.reports.edit', $report) }}" class="btn btn-outline-primary">{{ __('Edit report')
            }}</a>
            @endcan

            @yield('actions')

            @can('delete reports')
                {!! Form::open(['route' => ['report.reports.destroy', $report],
                                            'method' => 'DELETE',
                                            'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $report->getName()]),
                                            'class' => 'float-right'
                                           ])
                !!}
                    <button class="btn btn-outline-danger">
                        {{ __('Delete report') }}
                    </button>
                {!! Form::close() !!}
            @endcan

        </div>
    </div>

@stop
