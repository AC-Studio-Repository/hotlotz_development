@extends('appshell::layouts.default')

@section('title')
    {{ __('FAQ Categories') }}
@stop

@section('content')

    @if (Session::has('message'))
        <div class="alert alert-danger">
        {{ Session::get('message') }}
        </div>
    @endif

    <!-- @if (Session::has('delete'))
        <div class="alert alert-success">
        {{ Session::get('delete') }}
        </div>
    @endif -->

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create customers')
                <a href="{{ route('faq_category.faqcategories.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create FAQ Category') }}
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
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($faqcategories as $faqcategory)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('faq_category.faqcategories.show', $faqcategory) }}">{{ $faqcategory->name }}</a>
                            </span>
                        </td>
                        <td>
                            <div class="mt-2">
                                @can('edit customers')
                                    <a href="{{route('faq_category.faqcategories.edit',$faqcategory)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan

                                @can('delete customers')
                                    {!! Form::open(['route' => ['faq_category.faqcategories.destroy',$faqcategory->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $faqcategory->name])
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
            @if($faqcategories->hasPages())
                <hr>
                <nav>
                    {{ $faqcategories->links() }}
                </nav>
            @endif
        </div>
    </div>

@stop
