@extends('appshell::layouts.default')

@section('title')
    {{ __('Marketings') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
            @can('create marketings')
            <div class="card-actionbar">
                <a href="{{ route('marketing.marketings.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Marketing') }}
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
                @foreach($marketings as $marketing)
                    <tr>
                        <td>
                            @can('view marketings')
                                <span class="font-lg mb-3 font-weight-bold">
                                <a href="#"></a>

                                </span>
                            @else
                                <div class="text-muted">

                                </div>
                            @endcan
                        </td>
                        <td>
                            <span class="mb-3">

                            </span>
                            <div class="text-muted">

                            </div>
                        </td>
                        <td>
                            <div class="mt-2">
                                @can('edit marketings')
                                <a href="{{route('marketing.marketings.edit',$marketing)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan
                                @can('delete marketings')
                                {!! Form::open(['route' => ['marketing.marketings.destroy',$marketing->id],
                                            'method' => 'DELETE',
                                            'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => ''])
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
            @if(count($marketings) > 0)
                <hr>
                <nav>
                    {{ $marketings->links() }}
                </nav>
            @endif
        </div>
    </div>

@stop
