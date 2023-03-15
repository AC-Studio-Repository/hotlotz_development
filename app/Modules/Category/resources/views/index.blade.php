@extends('appshell::layouts.default')

@section('title')
    {{ __('Categories') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
            @can('create categories')
            <div class="card-actionbar">
                    <a href="{{ route('category.categories.create') }}" class="btn btn-sm btn-outline-success float-right">
                        <i class="zmdi zmdi-plus"></i>
                        {{ __('Create Category') }}
                    </a>
            </div>
            @endcan

        </div>

        <div class="card-block">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Category Name') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($categories as $category)
                    <tr onclick="window.location='{{ route('category.categories.show', $category) }}';" style="cursor: pointer;">
                        <td>
                            @can('view categories')
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('category.categories.show', $category) }}">{{ $category->name }}</a>
                            </span>
                            @else
                             <span class="text-muted">
                                {{ $category->name }}
                             </span>
                            @endcan
                        </td>
                        <td>
                            @can('edit categories')
                            <a href="{{ route('category.categories.edit', $category) }}"
                               class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            @if($categories->hasPages())
                <hr>
                <nav>
                    {{ $categories->links() }}
                </nav>
            @endif

        </div>
    </div>

@stop
