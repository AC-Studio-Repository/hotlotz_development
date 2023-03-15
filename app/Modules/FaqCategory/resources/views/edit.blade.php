@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $faqcategory->getName() }}
@stop

@section('content')
<div class="card card-accent-secondary">
    <div class="card-header">
        {{ __('FAQ Category Details') }}
    </div>

    {!! Form::model($faqcategory, ['route' => ['faq_category.faqcategories.update', $faqcategory], 'method' => 'PUT']) !!}

    <div class="card-block">
            @include('faq_category::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Update FAQ Category') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
