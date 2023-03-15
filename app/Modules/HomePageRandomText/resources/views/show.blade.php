@extends('appshell::layouts.default')

@section('title')
    {{ __('Ticker Display Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'layers',
                    'type' => 'success'
            ])
                {{ $random_text->title }}
                @slot('subtitle')
                    {{ $random_text->description }}
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
                    {{ $random_text->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('home_page_random_text.home_page_random_texts.edit', $random_text) }}" class="btn btn-outline-primary">{{ __('Edit Ticker Display')
            }}</a>

            @yield('actions')

            {!! Form::open(['route' => ['home_page_random_text.home_page_random_texts.destroy', $random_text],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $random_text->title]),
                                        'class' => 'float-right'
                                       ])
            !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Ticker Display') }}
                </button>
            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-3">
                {{ __('Title : ') }}
            </label>
            <label class="control-label col-md-6">
                {{ $random_text->name }}
            </label>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-3">
                {{ __('Description : ') }}
            </label>
            <label class="control-label col-md-6">
                {{ $random_text->description }}
            </label>
        </div>
    </div>
@stop
