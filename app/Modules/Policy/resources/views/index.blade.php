@extends('appshell::layouts.default')

@section('title')
    {{ __('Policy') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create customers')
                <a href="{{ route('policy.policies.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Policy') }}
                </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Menu') }}</th>
                    <th>{{ __('Title Header') }}</th>
                    <th>{{ __('File') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($policies as $policy)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('policy.policies.show', $policy) }}">{{ $policy->menu_name }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                               {{ $policy->title }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ $policy->file_path }}" width="60px" height="60px">File</a>
                        </td>
                        <td>
                            <div class="mt-2">
                                @can('edit customers')
                                    <a href="{{route('policy.policies.edit',$policy)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan

                                @can('delete customers')
                                    {!! Form::open(['route' => ['policy.policies.destroy',$policy->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $policy->menu_name])
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
            @if($policies->hasPages())
            <hr>
            <nav>
                {{ $policies->links() }}
            </nav>
        @endif
        </div>
    </div>

@stop
