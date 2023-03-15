@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new FAQ category') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Enter FAQ Category Name') }}

    </div>

    {!! Form::model($faq_category, ['route' => 'faq_category.faqcategories.store', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            @include('faq_category::_form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create FAQ Category') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop
