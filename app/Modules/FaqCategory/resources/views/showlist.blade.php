@extends('appshell::layouts.default')

@section('title')
    {{ __('FAQS') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
                <table class="table table-hover" style="border: 1px solid #c9d0d0;">
                        <tr>
                            <td><a href="{{ route('faq_category.faqcategories.faqCategoryList') }}" class="btn btn-outline-primary">{{ __('FAQ Categories') }}</a></td>
                        </tr>
                        <tr>
                            <td><a href="{{ route('faq.faqs.index') }}" class="btn btn-outline-primary">{{ __('FAQ') }}</a></td>
                        </tr>
                        <tr>
                            <td><a href="{{ route('faq.faqs.infoIndex') }}" class="btn btn-outline-primary">{{ __('FAQ Main Page') }}</a></td>
                        </tr>
                </table>
        </div>
    </div>

@stop
