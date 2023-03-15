@extends('appshell::layouts.default')

@section('title')
    {{ __('FAQ Category Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'layers',
                    'type' => 'success'
            ])
                {{ $faqcategory->getName() }}
                @slot('subtitle')
                    {{ $faqcategory->name }}
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
                    {{ $faqcategory->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('faq_category.faqcategories.edit', $faqcategory) }}" class="btn btn-outline-primary">{{ __('Edit FAQ Category')
            }}</a>

            @yield('actions')

            {!! Form::open(['route' => ['faq_category.faqcategories.destroy', $faqcategory],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $faqcategory->getName()]),
                                        'class' => 'float-right'
                                       ])
            !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete FAQ Category') }}
                </button>
            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-3">
                {{ __('FAQ Category Name : ') }}
            </label>
            <label class="control-label col-md-6">
                {{ $faqcategory->name }}
            </label>
        </div>
    </div>
@stop
