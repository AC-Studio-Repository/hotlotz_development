@extends('appshell::layouts.default')

@section('title')
    {{ __('Sell With Us FAQ') }}
@stop

@section('content')

    @if (Session::has('delete'))
        <div class="alert alert-success">
        {{ Session::get('delete') }}
        </div>
    @endif

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create customers')
                <a href="{{ route('sell_with_us.sell_with_uss.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Sell With Us FAQ') }}
                </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Question') }}</th>
                    <th>{{ __('Answer') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($sell_with_us_data as $sellWithUsFaq)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('sell_with_us.sell_with_uss.show', $sellWithUsFaq) }}">{{ $sellWithUsFaq->question }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">{{ $sellWithUsFaq->answer }}
                            </span>
                        </td>
                        <td>
                            <div class="mt-2">
                                @can('edit customers')
                                    <a href="{{route('sell_with_us.sell_with_uss.edit',$sellWithUsFaq)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan

                                @can('delete customers')
                                    {!! Form::open(['route' => ['sell_with_us.sell_with_uss.destroy',$sellWithUsFaq->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' => __('Are you sure to delete Q&A?')
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
            @if($sell_with_us_data->hasPages())
                <hr>
                <nav>
                    {{ $sell_with_us_data->links() }}
                </nav>
            @endif
        </div>
    </div>

@stop
