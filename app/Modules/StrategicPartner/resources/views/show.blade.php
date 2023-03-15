@extends('appshell::layouts.default')

@section('title')
    {{ __('Strategic Partner Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'layers',
                    'type' => 'success'
            ])
                {{ __('Strategic Partner') }}
                @slot('subtitle')
                    {{ __('Title') }}
                    {{ $strategic_partner->title }}
                @endslot
                @slot('subtitle')
                    {{ __('Description') }}
                    {{ $strategic_partner->description }}
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
                    {{ $strategic_partner->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('strategic_partner.strategic_partners.edit', $strategic_partner) }}" class="btn btn-outline-primary">{{ __('Edit Strategic Partner')
            }}</a>

            @yield('actions')

            {!! Form::open(['route' => ['strategic_partner.strategic_partners.destroy', $strategic_partner],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete this?'),
                                        'class' => 'float-right'
                                       ])
            !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Strategic Partner') }}
                </button>
            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-12">
                {{ $strategic_partner->title }}
            </label>
            <label class="control-label col-md-6">
                {{ $strategic_partner->description }}
            </label>
            <label class="control-label col-md-6">
                <img onclick="imagepreview(this)" lazyload="on" alt="Item Image" src="{{ $strategic_partner->full_file_path }}" width="576px" height="576px">
            </label>
        </div>
    </div>
@stop
