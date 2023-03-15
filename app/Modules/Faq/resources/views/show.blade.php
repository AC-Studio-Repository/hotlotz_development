@extends('appshell::layouts.default')

@section('title')
    {{ __('FAQ Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-6">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'layers',
                    'type' => 'success'
            ])
                {{ $faq->faqcategory->name }}
                @slot('subtitle')
                    {{ __('Question') }}
                    {{ $faq->question }}
                @endslot
                @slot('subtitle')
                    {{ __('Answer') }}
                    {{ $faq->answer }}
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
                    {{ $faq->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        @yield('widgets')

    </div>

    @yield('cards')

    <div class="card">
        <div class="card-block">
            <a href="{{ route('faq.faqs.edit', $faq) }}" class="btn btn-outline-primary">{{ __('Edit FAQ')
            }}</a>

            @yield('actions')

            {!! Form::open(['route' => ['faq.faqs.destroy', $faq],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete this?'),
                                        'class' => 'float-right'
                                       ])
            !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete FAQ') }}
                </button>
            {!! Form::close() !!}

        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-12 col-md-12">
            <label class="control-label col-md-12">
                {{ $faq->faqcategory->name }}
            </label>
            <label class="control-label col-md-6">
                {{ $faq->question }}
            </label>
            <label class="control-label col-md-6">
                {{ $faq->answer }}
            </label>
        </div>
    </div>
@stop
