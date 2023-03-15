@extends('appshell::layouts.default')

@section('title')
    {{ __('Viewing') }} {{ $email_template->getName() }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => enum_icon($email_template->type),
                    'type' => $email_template->is_active ? 'success' : 'warning'
            ])
                {{ $email_template->getName() }}
                @if (!$email_template->is_active)
                    <small>
                        <span class="badge badge-default">
                            {{ __('inactive') }}
                        </span>
                    </small>
                @endif
                @slot('subtitle')
                    {{ $email_template->email_template }}
                    {{ $email_template->phone }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'time-countdown',
                    'type' => $email_template->last_purchase_at ? 'success' : null
            ])
                {{ __('Last purchase') }}
                <span title="{{ $email_template->last_purchase_at ? $email_template->last_purchase_at : '' }}">{{ $email_template->last_purchase_at ? $email_template->last_purchase_at->diffForHumans() : __('never') }}</span>

                @slot('subtitle')
                    {{ __('Email since') }}
                    {{ $email_template->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            @can('edit email_templates')
            <a href="{{ route('email_template.email_templates.edit', $email_template) }}" class="btn btn-outline-primary">{{ __('Edit email template')
            }}</a>
            @endcan

            @yield('actions')

            @can('delete email_templates')
                {!! Form::open(['route' => ['email_template.email_templates.destroy', $email_template],
                                            'method' => 'DELETE',
                                            'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $email_template->getName()]),
                                            'class' => 'float-right'
                                           ])
                !!}
                    <button class="btn btn-outline-danger">
                        {{ __('Delete email template') }}
                    </button>
                {!! Form::close() !!}
            @endcan

        </div>
    </div>

@stop
