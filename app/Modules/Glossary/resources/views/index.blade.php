@extends('appshell::layouts.default')

@section('title')
    {{ __('Glossary') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create customers')
                <a href="{{ route('glossary.glossarys.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Glossary') }}
                </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
        <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th style="width: 20%">{{ __('Category Name') }}</th>
                    <th>{{ __('Term') }}</th>
                    <th>{{ __('Explanation') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($glossarys as $glossary)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('glossary.glossarys.show', $glossary) }}">{{ $glossary->glossarycategory->name }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ $glossary->question }}
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                               {{ $glossary->answer }}
                            </span>
                        </td>
                        <td>
                            <div class="mt-2">
                                @can('edit customers')
                                    <a href="{{route('glossary.glossarys.edit',$glossary)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan

                                @can('delete customers')
                                    {!! Form::open(['route' => ['glossary.glossarys.destroy',$glossary->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' => __('Are you sure to delete Q&A of this category :name?', ['name' => $glossary->glossarycategory->name])
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

        @if($glossarys->hasPages())
            <hr>
            <nav>
                {{ $glossarys->links() }}
            </nav>
        @endif
        </div>
    </div>

@stop
