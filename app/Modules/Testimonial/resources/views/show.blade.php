@extends('appshell::layouts.default')

@section('title')
    {{ __('Testimonial Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'layers',
                    'type' => 'success'
            ])
                {{ $testimonial->getQuote() }}
                @slot('subtitle')
                    {{ $testimonial->author }}
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
                    {{ $testimonial->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('testimonial.testimonials.edit', $testimonial) }}" class="btn btn-outline-primary">{{ __('Edit Testimonial')
            }}</a>

            @yield('actions')

            {!! Form::open(['route' => ['testimonial.testimonials.destroy', $testimonial],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete this testimonial?'),
                                        'class' => 'float-right'
                                       ])
            !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Testimonial') }}
                </button>
            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-3">
                {{ __('Testimonial Quote : ') }}
            </label>
            <label class="control-label col-md-6">
                {{ $testimonial->quote }}
            </label>
        </div>

        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-3">
                {{ __('Testimonial Author : ') }}
            </label>
            <label class="control-label col-md-6">
                {{ $testimonial->author }}
            </label>
        </div>
    </div>
@stop
