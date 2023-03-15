@extends('appshell::layouts.default')

@section('title')
    {{ __('Glossary Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'layers',
                    'type' => 'success'
            ])
                {{ $glossary->glossarycategory->name }}
                @slot('subtitle')
                    {{ __('Question') }}
                    {{ $glossary->question }}
                @endslot
                @slot('subtitle')
                    {{ __('Answer') }}
                    {{ $glossary->answer }}
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
                    {{ $glossary->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('glossary.glossarys.edit', $glossary) }}" class="btn btn-outline-primary">{{ __('Edit Glossary')
            }}</a>

            @yield('actions')

            {!! Form::open(['route' => ['glossary.glossarys.destroy', $glossary],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete this?'),
                                        'class' => 'float-right'
                                       ])
            !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Glossary') }}
                </button>
            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-12">
                {{ $glossary->glossarycategory->name }}
            </label>
            <label class="control-label col-md-6">
                {{ $glossary->question }}
            </label>
            <label class="control-label col-md-6">
                {{ $glossary->answer }}
            </label>
        </div>
    </div>
@stop
