@extends('appshell::layouts.default')

@section('title')
    {{ __('FAQ') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create customers')
                <a href="{{ route('faq.faqs.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create FAQ') }}
                </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Category Name') }}</th>
                    <th>{{ __('Question') }}</th>
                    <th>{{ __('Answer') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($faqs as $faq)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('faq.faqs.show', $faq) }}">{{ $faq->faqcategory->name }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('faq.faqs.show', $faq) }}">{{ $faq->question }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('faq.faqs.show', $faq) }}">{{ $faq->answer }}</a>
                            </span>
                        </td>
                        <td>
                            <div class="mt-2">
                                @can('edit customers')
                                    <a href="{{route('faq.faqs.edit',$faq)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan

                                @can('delete customers')
                                    {!! Form::open(['route' => ['faq.faqs.destroy',$faq->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' => __('Are you sure to delete Q&A of this category :name?', ['name' => $faq->faqcategory->name])
                                                ])
                                        !!}
                                    <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
                                    {!! Form::close() !!}
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            </div>
            @if($faqs->hasPages())
            <hr>
            <nav>
                {{ $faqs->links() }}
            </nav>
        @endif
        </div>
    </div>

@stop
