@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }}
@stop

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header">
        {{ __('Testimonial Details') }}
    </div>

    {!! Form::model($testimonial, ['route' => ['testimonial.testimonials.update', $testimonial], 'method' => 'PUT']) !!}

    <div class="card-block">
            @include('testimonial::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Update Testimonial') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
