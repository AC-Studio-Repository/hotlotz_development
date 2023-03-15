@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new Testimonial') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Enter Testimonial') }}

    </div>

    {!! Form::model($testimonial, ['route' => 'testimonial.testimonials.store', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            @include('testimonial::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create Testimonial') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
