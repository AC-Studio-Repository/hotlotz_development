@extends('appshell::layouts.default')

@section('title')
    {{ __('Marketplaces') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
            @can('create marketplaces')
            <div class="card-actionbar">
                <a href="{{ route('marketplace.marketplaces.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Marketplace') }}
                </a>
            </div>
            @endcan

        </div>

        <div class="card-block">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($marketplaces as $marketplace)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                @can('view marketplaces')
                                    <a href="#"></a>
                                @else

                                @endcan
                            </span>
                            <div class="text-muted">

                            </div>
                        </td>
                        <td>
                            <span class="mb-3">

                            </span>
                            <div class="text-muted">

                            </div>
                        </td>
                        <td>
                            <div class="mt-2">
                                <a href="{{route('marketplace.marketplaces.edit',$marketplace)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>

                                {!! Form::open(['route' => ['marketplace.marketplaces.destroy',$marketplace->id],
                                            'method' => 'DELETE',
                                            'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => ''])
                                            ])
                                    !!}
                                <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </div>

@stop
